<html>
    <head>
        <title>Send Sms</title>
    </head>
    <body>
        
        <form onsubmit="SendSms();">
            <button type="submit">Send</button>
        </form>
        
        
        
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
 
 <script>
 
 function SendSms()
 {
     var myHeaders = new Headers();
var raw = "";
var requestOptions = {
	method: 'GET',
	headers: myHeaders,
	body: raw,
	redirect: 'follow'
};
fetch("https://my.jisort.com/messenger/send_message/?username=trapflixbulksms@gmail.com&password=9000@Kenya&recipients=+254727014069&message=test", requestOptions)
    .then(response => response.text())
    .then(result => console.log(result))
    .catch(error => console.log('error', error));
 }

 </script>       
        
    </body>
</html>