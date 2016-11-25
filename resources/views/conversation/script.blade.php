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
                            <div class="row message">
                                <div class="col-md-8  col-md-push-4">
                                    <div class="pull-right">
                                        <button class="btn">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row message">
                                <div class="col-md-8">
                                    <div class="pull-left">
                                        <button class="btn">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <input name="user_text" placeholder="text your message" class="form-control">

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
            margin-bottom: 10px;
        }
    </style>
@endsection

@section('my_script')
    <script src="{{ url('js/emojione.min.js') }}"></script>
    <script src="{{ url('js/singularize.js') }}"></script>
    <script>
        let btnSendMsg = $('#btnSendMsg');
        let userReplyInput = $('input[name="user_text"]');
        let selectChatbox = $('select[name="chatbox_name"]');
        let conversationDiv = $('#conversation');

        let userReplyTemplate = $('<div class="row message">\n<div class="col-md-8  col-md-push-4">\n<div class="pull-right">\n<button class="btn"><\/button>\n<\/div>\n<\/div>\n<\/div>');
        let chatboxReplyTemplate = $('<div class="row message">\n<div class="col-md-8">\n<div class="pull-left">\n<button class="btn"><img src="{{ url('images/icon_status.gif') }}"><\/button>\n<\/div>\n<\/div>\n<\/div>');

        let audio = new Audio('{{ url("sounds/all-eyes-on-me.mp3") }}');

        btnSendMsg.on('click', handleMessage);
        $(document).keypress(function(e) {
            if(e.which == 13)
                handleMessage();

            console.log('press key');
        });

        function handleMessage(){
            let user_text = userReplyInput.val();
//            console.log('emojione convert', emojione.toShort(user_reply));
            {{--let isUserOnlyUseEmoji = function(text){--}}
                {{--let r = false;--}}
                {{--let emojiEmoticonPattern = /(:\w+:|<[\/\\]?3|[\(\)\\\D|\*\$][\-\^]?[:;=]|[:;=B8][\-\^]?[3DOPp@\$\*\\\)\(\/\|])(?=\s|[!\.\?]|$)/g;--}}
                {{--//Convert ANY NATIVE UNICODE to shortname--}}
                {{--text = emojione.toShort(text);--}}
                {{--console.log('text after emojione navtice>shortname convert', text);--}}
{{--//                if(text.match(emojiEmoticonPattern)){--}}
{{--//                    console.log('match emoji, emoticon in reply');--}}
{{--//                    text = text.replace(emojiEmoticonPattern, '');--}}
{{--//                    console.log('text after replace emoji, emoticon', text);--}}
{{--//                    r = (text == '');--}}
{{--//                }--}}
                {{--while(text.match(emojiEmoticonPattern)){--}}
                    {{--text = text.replace(emojiEmoticonPattern, '');--}}
                {{--}--}}
{{--//                console.log('text after replace ALL match emoji, emoticon', text);--}}
                {{--console.log('text after replcae ALlL emoji, emoticon');console.log(text);--}}
                {{--text = text.replace(/s+/g, '');--}}
                {{--r = (text == '');--}}
                {{--console.log(r);--}}

                {{--return r;--}}
            {{--}--}}

            let chatbox_name = selectChatbox.val();

            //append to conversationDiv
            let userReplyTmp = userReplyTemplate.clone();
            userReplyTmp.find('button').text(user_text);
            conversationDiv.append(userReplyTmp);
            conversationDiv.scrollTop(10000);

            //clear input text
            userReplyInput.val('');

//            if(isUserOnlyUseEmoji(user_text)){
//                //Do nothing
//                return
//            }

            let chatboxReply = chatboxReplyTemplate.clone();
            conversationDiv.append(chatboxReply);

            conversationDiv.scrollTop(10000);

//            let transformToSingular = function(text){
//                /**
//                 * This function can use BCS i have injected into global
//                 * check singularize.js
//                 */
//                let textSingleWords = text.split(' ');
//                textSingleWords = textSingleWords.map(function(word){
////                    let singularWord = singularize(word);
////                    let pluralWord = pluralize(singularWord);
////
////                    // I HAVE TO CHECK THIS bcs pluarlize/sigularize may fail
////                    // when fail it manipulate on user_reply WRONG
////                    return pluralWord == word ? singularWord : word;
//                    //Simple return sigularize from word
//                    //Serve use BOTH user_reply & user_reply_origin
//                    return singularize(word);
//                })
//
//                return textSingleWords.join(' ');
//            }
            /**
             * Change user type in with plural to singularize
             * SO DANGER when manipulate on user_reply this way
             * AAAA
             */
//            let user_text_origin = user_text;
//            user_text = transformToSingular(user_text);
            console.log('text after change to singular');console.log(user_text);

            $.post({
                url: '{{ url("script") }}',
                data: {
                    // bcs i've manipulate on user_reply, let the origin here
//                    user_text_origin,
                    user_text,
                    chatbox_name
                },
                success(res){
                    console.log(res);

                    if(res['response'] == 'I hear you, but no anwser'){
//                        console.log('Listen, but no answer case');
                        chatboxReply.find('button').remove();
                        return;
                    }

                    chatboxReply.find('button').text(res['response']);
                    conversationDiv.scrollTop(10000);
                    audio.play();
                },
                error(res){
                    console.log(res);
                }
            });
        }
    </script>
@endsection