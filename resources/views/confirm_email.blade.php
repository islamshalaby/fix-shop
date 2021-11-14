<html>

<body style="background-color:#e2e1e0;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
  <h2>تأكيد الحساب</h2>
  <h4>Hi, {{ $user->name }}</h1>
<br/>
<b>لتأكيد حسابك  </b> <a target="_blank" href="{{ route('front.activate.account', $user->remember_token) }}">إضغط هنا</a>
  
</body>

</html>