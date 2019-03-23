@extends('layouts.app')

@section('style')
    <style>
        .preloader-container {
            position: fixed;
            z-index: 1031;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: #ffffff78;
            display: block;
            overflow: hidden;
            padding: 25%;
        }

        .preloader-center {
            position: absolute;
            padding: 15px;
            top: 48%;
            left: 48%;
            -ms-transform: translateX(-50%) translateY(-50%);
            -webkit-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            background: transparent;
            z-index: 1000;
            font-size: 60px;
        }
        .hidden{
            display:none;

        }


        .loader-container {
            height: 90vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .circle {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #ff1ead;
        }
        .circle:not(:last-child) {
            margin-right: 10px;
        }
        .circle-1 {
            animation: moveup 0.9s infinite;
        }
        .circle-2 {
            animation: moveup 0.9s 0.3s infinite;
        }
        .circle-3 {
            animation: moveup 0.9s 0.6s infinite;
        }
        @keyframes moveup {
            0% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
            100% {
                transform: translateY(0);
            }
        }

    </style>
@endsection

@section('content')
    <div id="js-busy-loader" class="preloader-container js-busy-loader hidden">
        <div class="preloader-center">
            <div class="loader-container">
                <div class="circle circle-1"></div>
                <div class="circle circle-2"></div>
                <div class="circle circle-3"></div>
            </div>

        </div>
    </div>
    <div class="container">
        <links-resource :resources="{{json_encode($links, true)}}"></links-resource>
    </div>
@endsection
