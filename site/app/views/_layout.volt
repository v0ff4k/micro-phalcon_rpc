<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="UTF-8">
    <meta content="text/html; charset=utf-8" http-equiv="content-type">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta content="authenticity_token" name="csrf-param"/>
    <meta content="{ web.getCsrfToken }" name="csrf-token"/>
    <title>{% block title %}{% endblock %}</title>
    <meta name="keywords" content="{% block keywords %}{% endblock %}">
    <meta name="description" content="{% block description %}{% endblock %}">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />

    <link rel="dns-prefetch" href="//maxcdn.bootstrapcdn.com">
    <link rel="dns-prefetch" href="//use.fontawesome.com">

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/4.0.2/bootstrap-material-design.css"
          integrity="sha256-c9OCpXgYepI8baar2x81YigAWryLIoQ2k0/7cCb1JAg=" crossorigin="anonymous" />
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" />
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    {% block stylesheets %}
        <!--link rel="stylesheet" href="" /-->
    {% endblock %}


</head>
<body class="{% block body_class %}{% endblock %}">
{% block header %}
    <header>{% include '_message.volt' %}</header>
{% endblock %}
<div class="container">
    {% block content %}
    {{ content() }}
    {% endblock %}
</div>
{% block footer %}
    <footer class="panel-footer">
        <div class="container"> just for test </div>
    </footer>
{% endblock %}


{% block javascripts %}
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/4.0.2/bootstrap-material-design.umd.min.js" integrity="sha256-GYcdwXot2kSaBb8kr9o8zDKYBwQ1PCkugjcCYFQS+IA=" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-dropdown/2.0.3/jquery.dropdown.min.js" integrity="sha256-WjSLNFIPnKGDcCD43ypegq+F+/M0WFws4KmtyOVsf0g=" crossorigin="anonymous"></script>
    <script src="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <!--script src=""></script-->
{% endblock %}
</body>
</html>