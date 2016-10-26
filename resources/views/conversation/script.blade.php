@extends('layouts.app')

@section('content')
    <div class="panel panel-default">
        <h1 class="panel-heading">Test conversation script</h1>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <h4>Pick your chatbox</h4>
                        <select name="chatbox_name" class="form-control">
                            @foreach($conversations as $conversation)
                                <option value="{{ $conversation->name }}">{{ $conversation->name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group">
                        <h4>Conversation</h4>
                        <div id="conversation" class="form-control">
                            <div class="row">
                                <div class="col-md-6  col-md-push-6">
                                    <div class="pull-right">
                                        <button class="btn btn-success">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row message">
                                <div class="col-md-6">
                                    <div class="pull-left">
                                        <button class="btn btn-info">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6  col-md-push-6">
                                    <div class="pull-right">
                                        <button class="btn btn-success">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row message">
                                <div class="col-md-6">
                                    <div class="pull-left">
                                        <button class="btn btn-info">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="input-group">
                            <input name="user_reply" placeholder="text your message" class="form-control">

                            <span class="input-group-btn">
                                <button class="btn btn-info" id="btnSendMsg">
                                    <i class="fa fa-comments"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        #conversation{
            height: 300px;
            overflow-y: scroll;
            overflow-x: hidden;
            padding-right: 20px;
        }

        .btn {
            white-space: normal;
            text-align: left;
        }

        .message{
            position: relative;
            top: -20px;
        }
    </style>
@endsection

@section('my_script')
    <script>
        let btnSendMsg = $('#btnSendMsg');
        let userReplyInput = $('input[name="user_reply"]');
        let selectChatbox = $('select[name="chatbox_name"]');
        let conversationDiv = $('#conversation');
        btnSendMsg.on('click', function(){
            let user_reply = userReplyInput.val();
            let chatbox_name = selectChatbox.val();

            //append to conversationDiv

            $.post({
                url: '{{ url("script") }}',
                data: {
                    user_reply,
                    chatbox_name
                },
                sucess(res){
                    console.log(res);
                },
                error(res){
                    console.log(res);
                }
            });
        });
    </script>
@endsection