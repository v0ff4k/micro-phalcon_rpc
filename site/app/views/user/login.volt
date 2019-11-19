{% extends '_layout.volt' %}
{% block body_class %}login{% endblock %}

{% block title %}Auth{% endblock %}

{% block header %}
    {{ super() }}
    Auth.....
{% endblock %}

{% block content %}

    {% if response is defined %}
        <div class="alert alert-success" role="alert">
            {{ response }}
            {#<? $this->flashSession->output(); ?>#}
        </div>
    {% else %}

    <div class="row justify-content-md-center mt-2">
        <div class="col-6">

            {{ form('/login', 'method': 'post') }}{{ form.render('csrf', ['value': security.getToken()]) }}
                <div class="card">
                    <div class="card-header">Auth</div>
                    <div class="card-body">
                        <div class="form-group">
                            {#<label for="login">login</label>#}
                            <input type="text" class="form-control {{ error['login'] is defined ? 'is-invalid' : '' }}"
                                   name="login" placeholder="Login"
                                   value="{{ login is defined ? login : '' }}">
                            {% if error['login'] is defined %}
                                <span class="text-danger">
                                    {% set eLogin = error['login']|join('<br />') %}
                                    {{ eLogin }}
                                </span>
                            {% endif %}
                        </div>
                        <div class="form-group">
                            {#<label for="password">password</label>#}
                            <input type="password" class="form-control {{ error['password'] is defined ? 'is-invalid' : '' }}" name="password" placeholder="Password"
                                   value="{{ password is defined ? password : '' }}">
                            {% if error['password'] is defined %}
                                {% set ePassword = error['password']|join('<br />') %}
                                <span class="text-danger">
                                    {{ ePassword }}
                                </span>
                            {% endif %}
                        </div>
                    </div>
                    <div class="card-footer">
                        {#<button type="submit" class="btn btn-primary pull-right">&nbsp;&gt;&gt;&gt;&nbsp;</button>#}
                        {{ form.render('go', ['class': 'btn btn-primary pull-right']) }}
                    </div>
                </div>
            {{ end_form() }}
        </div>
    </div>
    {% endif %}
{% endblock %}

{% block footer %}
    {{ super() }}
{% endblock %}

{% block javascripts %}
    {{ super() }}
{% endblock %}