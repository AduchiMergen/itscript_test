$(function() {
    var dialog = {
        id: 0,
        question: 'По какому поводу звоните?',
        type: 'radio',
        ansvers: [
            {value:1,label:'Звонок по поводу проблемы качества'},
            {value:2,label:'Вопрос по доставке'},
            {value:3,label:'Консультация/Другое'}
        ]
    }
    var dialog1 = {
        id: 1,
        question: 'Номер заказа?',
        type: 'text',
        ansvers: [
            {value:'',label:'Номер'}
        ]
    }
    window.DialogView = Backbone.View.extend({
        template: _.template($('#dialog-template').html()),
        attributes:function(){
            return {
                id:"dialog-message-"+this.model.get('id'),
                title:this.model.get('title','')
            };
        },
        initialize: function(){
            $('body').append(this.render().el);
            scope = this;
            this.$el.dialog({
                width: 500,
                modal:false,
                closeOnEscape: false,
                open: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); },
                buttons:{
                    Ok: function(){scope.work()}
                }
            });
        },
        render: function() {
          $(this.el).html(this.template(this.model.toJSON()));
          return this;
        },
        work:function(){
            $.post("backend.php",this.$el.find('form').serialize(),this.ansver,'json');
            // window.DialogView2 = new DialogView({model:new Backbone.Model(dialog1)});
        },
        ansver:function(data){
            if (data.next_dialog){
                new DialogView({model:new Backbone.Model(data.next_dialog)});
            }
            if (data.result){
                alert(data.result);
                window.location.reload();
            } 
            if (data.error){
                alert(data.error);
            }

        }
    });
    $.getJSON("backend.php",function(data){new DialogView({model:new Backbone.Model(data)});});
});