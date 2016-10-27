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
                        <span class="input-group-addon">Boyfriend</span>
                        <input type="file" name="excel_file" class="form-control">
                    </div>

                    <small class="text-muted">Upload your boyfriend file</small>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">Boyfriend_default</span>
                        <input type="file" name="excel_default_file" class="form-control">
                    </div>

                    <small class="text-muted">
                        Upload your boyfriend_default file, a fallback answer when keyword not found
                    </small>
                </div>

                <pre id="wbObjPre"></pre>

                <button id="btnLoad" class="btn btn-info">Load</button>
            </form>
        </div>
    </div>
    <style>
        #wbObjPre {
            white-space: normal;
            max-height: 200px;
            overflow-y: scroll;
            overflow-x: hidden;
        }
    </style>
@endsection

@section('my_script')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.min.js"></script>

    <script src="{{ url('js/parse-regex.js') }}"></script>

    <script>
        console.log(flashDiv);
        //for main excel file
        let excel_file = $('input[name="excel_file"]');
        let excelParsed = $('input[name="excel_parsed"]');
        //for default file
        let excel_default_file = $('input[name="excel_default_file"]');
        let excelDefaultParsed = $('input[name="excel_default_parsed"]');

        let form = $('form#loadChatbox');

        excel_file.on('change', handleFile);

        excel_default_file.on('change', handleDefaultFile);

        function handleFile(e){
            let files = e.target.files;
            let f1 = files[0];
            let fileReader = new FileReader();
            fileReader.readAsBinaryString(f1);

            fileReader.onload = function(e){
                let data = e.target.result;
                let wb = XLSX.read(data, {type: 'binary'});

                let sheetNames = wb.SheetNames;
                //wb always has a default sheet
                let firstSheet = wb.Sheets[sheetNames[0]];
                let wbObj = XLSX.utils.sheet_to_row_object_array(firstSheet);

                wbObj = wbObj
                        .filter(function(val){
                            //remove out where Keyword
                            return val['Keyword'];
                        })
                        .map(function(val){
                            //handle val['Keyword']
                            //PARSE IT into php preg_match
                            let keyword = val['Keyword'];
                            let pattern = parseKeyword(keyword);
                            val['Keyword'] = pattern;
                            return val;
                        });
                console.log('wbObj', wbObj);
                // console.log(wbObj);
                let wbObjPre = $('#wbObjPre');console.log(wbObjPre);
                let wbObjStr = JSON.stringify(wbObj);
                // wbObjPre.html(`<pre>${wbObjStr}</pre>`);
                wbObjPre.text(wbObjStr);
                excelParsed.val(wbObjStr);
            }
        }

        function handleDefaultFile(e){
            let files = e.target.files;
            let f1 = files[0];
            let fileReader = new FileReader();
            fileReader.readAsBinaryString(f1);
            
            fileReader.onload = function(e){
                let data = e.target.result;
                let wb = XLSX.read(data, {type: 'binary'});

                let sheetNames = wb.SheetNames;
                //wb always has a default sheet
                let wbSheet1 = wb.Sheets[sheetNames[0]];
                
                let wbSheet1Ref = wbSheet1['!ref'];
                let refRange = XLSX.utils.decode_range(wbSheet1Ref);
                
                let wbObj = [];
                for(var R = refRange.s.r; R <= refRange.e.r; ++R){
                    //only read the first col refRange.s.c
                    var cellAddress = {c: refRange.s.c, r: R};
                    var cellVal = wbSheet1[XLSX.utils.encode_cell(cellAddress)].v;
                    wbObj.push(cellVal);
                }

                let wbObjPre = $('#wbObjPre');
                let wbObjStr = JSON.stringify(wbObj);
//                wbObjPre.html(`<pre>${wbObjStr}</pre>`);
                wbObjPre.text(wbObjStr);
                excelDefaultParsed.val(wbObjStr);
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