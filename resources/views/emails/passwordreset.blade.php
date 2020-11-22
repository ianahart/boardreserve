<html>

<head>
  <style>
    h3 {
      color: #333;
    }

    span {
      color: #00b3b3;
      font-weight: bold;
    }

    a {
      display: block;
      color: #00b3b3;
    }
  </style>
</head>

<body>
  <h3>Hi, <span>{{ $passwordReset->name }}</span>, greetings from Board Reserve!</h3>
  <p>Below is your requested password reset link:</p>
  <a href="https://boardreserve.herokuapp.com/resetpassword?token={{$passwordReset->token}}">Password Reset</a>
  <p>You have <span>24 hours</span> to change your password before this link expires</p>

</body>

</html>