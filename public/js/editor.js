tinymce.init({
    selector: "div.editable",
    theme: "modern",
    plugins: [
        ["advlist autolink link pineimage lists charmap print preview hr anchor pagebreak spellchecker"],
        ["searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking"],
        ["save table contextmenu directionality emoticons template paste textcolor pinelink pinecode"]
    ],
    contextmenu: "link pinelink image | inserttable | cell row column deletetable",
    style_formats_merge: true,
    add_unload_trigger: false,
    schema: "html5",
    inline: true,
    toolbar1: "undo redo | styleselect | pinecode | bold underline italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image ",
    toolbar2: "forecolor backcolor | print preview media",
    statusbar: true,
    image_list: [],
    convert_urls: false,
    browser_spellcheck : true,
    setup : function(ed) {
        ed.on('KeyDown',function(e) {
            if (e.keyCode == 9 && !e.altKey && !e.ctrlKey){
                if (e.shiftKey){
                    tinyMCE.activeEditor.editorCommands.execCommand("outdent");
                }else{
                    tinyMCE.activeEditor.editorCommands.execCommand("indent");
                }
                return tinymce.dom.Event.cancel(e); 
            }else if (e.keyCode == 9 && !e.ctrlKey && e.altKey && !e.shiftKey){
                tinyMCE.activeEditor.editorCommands.execCommand('mceInsertContent', false, "&nbsp;&nbsp;&nbsp;");
                return tinymce.dom.Event.cancel(e); 
            }
            
        });
    },
    setup : function(ed) {
        ed.on('BeforeSetContent',function(e) {
            if(e.initial === true){
            }else{
                e.content = e.content.replace(/    /g,"&nbsp;&nbsp;&nbsp;&nbsp;");
                e.content = e.content.replace(/   /g,"&nbsp;&nbsp;&nbsp;");
                e.content = e.content.replace(/  /g,"&nbsp;&nbsp;");
            }
        });
    },
    paste_preprocess : function(pl, o) {
            //alert(o.content);
            //o.content = o.content.replace(/\>\s+\</g,"&nbsp;");
    }
});