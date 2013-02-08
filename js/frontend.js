$(function() {
    window.DialogView = Backbone.View.extend({
        template: _.template($('#dialog-template').html()),
        attributes:function(){
            title = this.model.get('title');
            return {
                id:"dialog-message-"+this.model.get('id'),
                title:(title)?title:'Client id:'+window.client_id
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
    window.client_id = 5;
    $.getJSON("backend.php",function(data){
            window.client_id = data.session_id;
            new DialogView({model:new Backbone.Model(data.node)});
        });
});