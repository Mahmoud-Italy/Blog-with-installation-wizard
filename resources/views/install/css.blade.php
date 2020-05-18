<style>
            body {
                font-family: "Open Sans", sans-serif;
                font-size: 16px;
            }

            h1, h2, h3, h4, h5, h6,
            ul, li,
            p {
                margin: 0;
                padding: 0;
            }

            h1, h2, h3, h4, h5, h6 {
                font-family: "Rubik", sans-serif;
                font-weight: 400;
                color: #555555;
            }

            div:active,
            div:focus,
            div:visited,
            a:active,
            a:focus,
            a:visited {
                outline: 0;
            }

            a {
                transition: 200ms ease-in-out;
            }

            .table-responsive {
                border: 1px solid #e9e9e9;
                border-radius: 3px;
                padding: 5px 15px 2px;
            }

            .table {
                margin-bottom: 0;
            }

            .table > thead > tr > th {
                border-bottom: 1px solid #d9d9d9;
            }

            .table > tbody > tr > td {
                border-color: #f1f1f1;
                padding: 12px 8px;
            }

            .form-horizontal .control-label {
                font-size: 15px;
                padding-top: 8px;
                text-align: left;
            }

            .control-label > span {
                color: #fc4b4b;
            }

            .form-control {
                border-color: #d9d9d9;
                border-radius: 3px;
                box-shadow: none;
                height: 40px;
                transition: 200ms ease-in-out;
            }

            .form-control:focus {
                box-shadow: none;
                border-color: #0068e1;
            }

            .help-block {
                font-size: 14px;
                margin-bottom: 5px;
            }

            .has-error .control-label,
            .has-error .help-block {
                color: #ff3366;
            }

            .has-error .form-control {
                border-color: #ff3366;
            }

            .has-error .form-control:focus {
                box-shadow: none;
                border-color: #ff3366;
            }

            .custom-select-black {
                appearance: none;
                -webkit-appearance: none;
                background: transparent url('../../public/modules/admin/images/arrow-black.png') no-repeat right 8px center;
                background-size: 10px;
            }

            .alert {
                font-family: "Rubik", sans-serif;
                border: none;
                border-radius: 3px;
                color: #ffffff;
            }

            .alert .close {
                color: #ffffff;
                opacity: 0.7;
                text-shadow: none;
                transition: 200ms ease-in-out;
            }

            .alert .close:hover {
                opacity: 1;
            }

            .alert-danger {
                background: #ff5252;
            }

            .alert .close > i {
                -webkit-text-stroke: 2px #ff5252;
            }

            h1 {
                font-size: 36px;
                line-height: 44px;
            }

            h2 {
                font-size: 30px;
                line-height: 36px;
            }

            h3 {
                font-size: 24px;
                line-height: 29px;
            }

            h4 {
                font-size: 21px;
                line-height: 26px;
            }

            h5 {
                font-size: 18px;
                line-height: 22px;
            }

            h6 {
                font-size: 16px;
                line-height: 20px;
            }

            p {
                font-size: 16px;
                line-height: 22px;
                color: #666666;
                letter-spacing: 0.2px;
            }

            .btn {
                font-family: "Open Sans", sans-serif;
                font-size: 16px;
                border: 1px solid;
                padding: 9px 20px;
                border-radius: 3px;
                background: transparent;
                color: #555555;
                letter-spacing: 0.2px;
                transition: 200ms ease-in-out;
                outline: 0 !important;
            }

            .btn-primary {
                background: #0068e1;
                color: #ffffff;
                border-color: #0068e1;
            }

            .btn-primary:active,
            .btn-primary:hover,
            .btn-primary:focus,
            .btn-primary:active:focus {
                background: #0059bd;
                border-color: #0059bd;
            }

            .btn-primary.disabled,
            .btn-primary[disabled] {
                opacity: 0.6;
            }

            .wrapper > .col-lg-8.col-md-9 {
                float: none;
                margin: auto;
            }

            .left-sidebar {
                border-right: 1px solid #e9e9e9;
                margin: 30px 0 0 0;
            }

            .left-sidebar li {
                position: relative;
                display: block;
                float: none;
                color: #777777;
                padding: 4px 0;
                cursor: default;
            }

            .left-sidebar li.active {
                font-weight: 600;
                color: #555555;
            }

            .left-sidebar li.complete {
                padding-left: 25px;
            }

            .left-sidebar li.complete:after {
                position: absolute;
                content:"\f00c";
                left: 0;
                top: 1px;
                font-family: FontAwesome;
                font-size: 20px;
                -webkit-text-stroke: 0.5px #ffffff;
                color: #37bc9b;
            }

            .content-wrapper {
                padding: 25px 0 30px;
            }

            .box {
                margin-top: 30px;
            }

            .box > p {
                margin-bottom: 12px;
            }

            .box .table tr > td:last-child > i {
                font-size: 20px;
                -webkit-text-stroke: 1px #ffffff;
            }

            .box .table tr > td > i.fa-check {
                color: #37bc9b;
            }

            .box .table tr > td > i.fa-times {
                color: #fc4b4b;
            }

            .configure-form {
                border: 1px solid #e9e9e9;
                border-radius: 3px;
                padding: 20px 15px 5px;
            }

            .installation-message {
                margin: 30px 0 60px;
            }

            .installation-message > i {
                font-size: 80px;
                color: #37bc9b;
                -webkit-text-stroke: 5px #ffffff;
            }

            .visit {
                border: 1px solid #f1f3f7;
                background: #f7f8f9;
                border-radius: 3px;
                padding: 40px 0;
            }

            .visit .icon {
                display: block;
                text-align: center;
                margin-bottom: 20px;
            }

            .visit .icon > i {
                font-size: 48px;
                color: #626060;
                -webkit-text-stroke: 1px #f5f5f5;
            }

            .visit .btn-primary {
                background: transparent;
                border: 1px solid #d9d9d9;
                color: #555555;
            }

            .visit .btn-primary:active,
            .visit .btn-primary:hover,
            .visit .btn-primary:focus {
                background: #0068e1;
                border-color: #0068e1;
                color: #ffffff;
            }

            .content-buttons {
                margin-top: 20px;
            }

            .p-b-0 {
                padding-bottom: 0;
            }

            .btn-loading {
                position: relative;
                color: transparent !important;
            }

            .btn-loading:after {
                position: absolute;
                content: "";
                left: 0;
                top: 0;
                right: 0;
                bottom: 0;
                margin: auto;
                height: 16px;
                width: 16px;
                border: 2px solid #ffffff;
                border-radius: 100%;
                border-right-color: transparent;
                border-top-color: transparent;
                animation: spinAround 600ms infinite linear;
            }

            .btn-loading.btn-default:after {
                border: 2px solid #0068e1;
                border-right-color: transparent;
                border-top-color: transparent;
            }

            @keyframes spinAround {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(359deg);
                }
            }

            @media screen and (max-width: 991px) {
                .left-sidebar {
                    border-right: none;
                }
            }

            @media screen and (max-width: 767px) {
                .form-horizontal .control-label {
                    padding-top: 0;
                }

                .configure-form {
                    padding-top: 15px;
                }

                .visit-wrapper > .row > .col-sm-6:last-child > .visit {
                    margin-top: 30px;
                }
            }
        </style>