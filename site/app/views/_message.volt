{% if flash is defined and flash%}
    <div class="alert alert-primary" role="alert">
        {{ flash.output() }}
        {#<? $this->flashSession->output(); ?>#}
    </div>
{% endif %}
