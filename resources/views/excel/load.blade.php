@extends('layouts.app')

@section('content')
    <div class="panel panel-default">
        <h1 class="panel-heading">Load excel file</h1>
        <div class="panel-body">
            <form action="" method="POST" id="loadChatbox" enctype="multipart/form-data">
                <input type="hidden" name="excel_parsed">
                <input type="hidden" name="excel_default_parsed">

                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">Boyfriend*.xlsx</span>
                        <input type="file" name="excel_file" class="form-control">
                    </div>

                    <small class="text-muted">Upload your boyfriend file</small>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">Boyfriend*_default.xlsx</span>
                        <input type="file" name="excel_default_file" class="form-control">
                    </div>

                    <small class="text-muted">Upload your boyfriend_default file, a fallback answer when keyword not found</small>
                </div>

                <pre id="wbJsonPre"></pre>

                <button id="btnLoad" class="btn btn-info">Load</button>
            </form>
        </div>
    </div>
    <style>
        #wbJsonPre{
            white-space: normal;
        }
    </style>
@endsection

@section('my_script')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.min.js"></script>
    {{--<script src="{{ url('js/flash.js') }}"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2014-11-29/FileSaver.min.js"></script>
    <script src="{{ url('js/parse-regex.js') }}"></script>
    <script>
        /**
         * Created by hoanganh25991 on 29/09/16.
         */
//        let html = '<p><a href="#download" id="download">Download<a> sample <strong>excel-file</strong></p>';
//        let flashDiv = $('.alert');
        console.log(flashDiv);
//        flashDiv.html(html);
//        flashDiv.attr('class', 'alert alert-info');

        let downloadBtn = $('#download');
        downloadBtn.on('click', function(e){
            e.preventDefault();
            console.log('click');
            let url = "{{ url('load-file?file_name=rooms.xlsx') }}";
            let win = window.open(url, '_blank');
            win.focus();
        });

        let excel_file = $('input[name="excel_file"]');

        let excel_default_file = $('input[name="excel_default_file"]');

        let excelParsed = $('input[name="excel_parsed"]');

        let excelDefaultParsed = $('input[name="excel_default_parsed"]');

        let form = $('form#loadChatbox');

        console.log(excel_file);

        excel_file.on('change', handleFile);

        excel_default_file.on('change', handleDefaultFile);

        function handleFile(e){
            let files = e.target.files;
            let f1 = files[0];
            let fileReader = new FileReader();
            fileReader.readAsBinaryString(f1);
            fileReader.onload = function(e){
                let data = e.target.result;
                //noinspection JSUnresolvedVariable
                let wb = XLSX.read(data, {type: 'binary'});

                //@warn: Sheet1 : hard-code
                //detect through sheet name array
                let wbJson = XLSX.utils.sheet_to_row_object_array(wb.Sheets['Sheet1']);
                wbJson.forEach(function(val){
                    //handle val['Keyword']
                    //PARSE IT into php preg_match
                    let keyword = val['Keyword'];
                    let pattern = parseKeyword(keyword);
                    val['Keyword'] = pattern;
                })
                ;
                // console.log(wbJson);
                let wbJsonPre = $('#wbJsonPre');
                let wbJsonStr = JSON.stringify(wbJson);
//                wbJsonPre.html(`<pre>${wbJsonStr}</pre>`);
                wbJsonPre.text(wbJsonStr);
                excelParsed.val(wbJsonStr);
            }
        };

        function handleDefaultFile(e){
            let files = e.target.files;
            let f1 = files[0];
            let fileReader = new FileReader();
            fileReader.readAsBinaryString(f1);
            fileReader.onload = function(e){
                let data = e.target.result;
                //noinspection JSUnresolvedVariable
                let wb = XLSX.read(data, {type: 'binary'});
                let wbSheet1 = wb.Sheets['Sheet1'];
                let wbSheet1Ref = wbSheet1['!ref'];
                let refRange = XLSX.utils.decode_range(wbSheet1Ref);
                let wbJson = [];
                for(var R = refRange.s.r; R <= refRange.e.r; ++R) {
                    for(var C = refRange.s.c; C <= refRange.e.c; ++C) {
                        var cell_address = {c:C, r:R};
                        var cellVal = wbSheet1[XLSX.utils.encode_cell(cell_address)].v;
                        wbJson.push(cellVal);
                    }
                }

                let wbJsonPre = $('#wbJsonPre');
                let wbJsonStr = JSON.stringify(wbJson);
//                wbJsonPre.html(`<pre>${wbJsonStr}</pre>`);
                wbJsonPre.text(wbJsonStr);
                excelDefaultParsed.val(wbJsonStr);
            }
        }

        let btnLoad = $('#btnLoad');
        btnLoad.on('click', function(e){
            e.preventDefault();
            let excelParsedJson = excelParsed.val();
            let excelDefaultParsedJson = excelDefaultParsed.val();

            if(!excelParsedJson.trim() || !excelDefaultParsedJson){
                flash(`Please upload <strong>both boyfriend & boyfriend_default file</strong>`, 'warning');
                return;
            }
            form.submit();
        });
    </script>
@endsection