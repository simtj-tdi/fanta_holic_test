<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="robots" content="noindex">
    <script language=javascript>
        <!--
        function openKMCISWindow(){
            document.reqKMCISForm.action = 'https://www.kmcert.com/kmcis/web/kmcisReq.jsp';
            document.reqKMCISForm.submit();
        }

        //-->
    </script>
</head>

<body onLoad="javascript:openKMCISWindow();">
<form name="reqKMCISForm" method="post" action="#">
    <input type="hidden" name="tr_cert"     value = "{{ $enc_tr_cert }}">
    <input type="hidden" name="tr_url"      value = "{{ $tr_url }}">
    <input type="hidden" name="tr_add"      value = "{{ $tr_add }}">
</form>
</body>
</html>