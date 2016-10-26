@extends('layouts.app')

@section('content')
    <div class="panel panel-default">
        <h1 class="panel-heading">Load rooms from excel-file</h1>
        <div class="panel-body">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">Excel-file</span>

                </div>
            </div>
            <p id="wbJsonPre"></p>
            <form action="" method="POST" id="loadChatbox" enctype="multipart/form-data">
                <input type="hidden" name="rooms">
                <input type="hidden" name="file_name">
                <input type="file" name="excel_file" class="form-control">
                <button id="btnLoad" class="btn btn-info">Load</button>
            </form>
        </div>
    </div>
@endsection

@section('my_script')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.min.js"></script>
    <script src="{{ url('js/flash.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2014-11-29/FileSaver.min.js"></script>
    <script>
        /**
         * Created by hoanganh25991 on 29/09/16.
         */
        {{--let html = '<p><a href="#download" id="download">Download<a> sample <strong>excel-file</strong></p>';--}}
        {{--let flashDiv = $('.alert');--}}
        {{--flashDiv.html(html);--}}
        {{--flashDiv.attr('class', 'alert alert-info');--}}

        {{--let downloadBtn = $('#download');--}}
        {{--downloadBtn.on('click', function(e){--}}
            {{--e.preventDefault();--}}
            {{--console.log('click');--}}
            {{--let url = "{{ url('load-file?file_name=rooms.xlsx') }}";--}}
            {{--let win = window.open(url, '_blank');--}}
            {{--win.focus();--}}
        {{--});--}}

        let excel_file = $('input[name="excel_file"]');

        let roomsInput = $('input[name="rooms"]');

        let filenameInput = $('input[name="file_name"]');

        let form = $('form#loadChatbox');

        excel_file.on('change', handleFile);

        function handleFile(e){
            let files = e.target.files;
            let f1 = files[0];
            filenameInput.val(f1.name);
            let fileReader = new FileReader();
            fileReader.readAsBinaryString(f1);
            fileReader.onload = function(e){
                let data = e.target.result;
                //noinspection JSUnresolvedVariable
                let wb = XLSX.read(data, {type: 'binary'});

                let wbJson = XLSX.utils.sheet_to_row_object_array(wb.Sheets['Sheet1']);
//                wbJson.forEach(function(val){
//                    var created_at = new Date().toISOString().slice(0, 19).replace('T', ' ');
//                    val.created_at = created_at;
//                    val.updated_at = created_at;
//                })
//                ;
                // console.log(wbJson);
                let wbJsonPre = $('#wbJsonPre');
                let wbJsonStr = JSON.stringify(wbJson);
                wbJsonPre.html(`<pre>${wbJsonStr}</pre>`);
                roomsInput.val(wbJsonStr);
            }
        };
        let btnLoad = $('#btnLoad');
        btnLoad.on('click', function(e){
            e.preventDefault();
            let roomsJson = roomsInput.val();
//            console.log(roomsJson);

            if(!roomsJson.trim()){
                flash(`Please upload <strong>excel-file</strong> first`, 'warning');
                return;
            }
//            console.log(roomsJson);
            form.submit();
        });
//        let reloadCheckbox = $('input[name="reload"]');
//        reloadCheckbox.on('click', function(){
//            if(reloadCheckbox.is(':checked'))
//                flash(`You will <strong>TRUNCATE</strong> table rooms, to reload`, 'danger');
//        });
    </script>
@endsection