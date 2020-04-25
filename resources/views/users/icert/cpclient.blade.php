<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="robots" content="noindex">
    <script language='javascript'>
        window.name ="Parent_window";
        function fnPopup(){
            // window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
            document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
            // document.form_chk.target = "popupChk";
            document.form_chk.submit();
        }
    </script>
</head>

<body onLoad="javascript:fnPopup();">
<form name="form_chk" method="post">
    <input type="hidden" name="m" value="checkplusSerivce">						<!-- 필수 데이타로, 누락하시면 안됩니다. -->
    <input type="hidden" name="EncodeData" value="{{ $enc_data }}">		<!-- 위에서 업체정보를 암호화 한 데이타입니다. -->
</form>
</body>
</html>