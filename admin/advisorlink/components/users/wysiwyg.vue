<template>
    <div>
      <textarea :id="id" :value="value"></textarea>
    </div>
</template>

<script>
module.exports = {	
    props: {
        id: {
            type: String,
            default: 'editor'
        },
        value: {
            type: String,
            default : ''
        },
        toolbar: {
            default: 'styleselect | bold italic | link | numlist bullist | alignleft aligncenter alignright | undo redo | paste'
        },
        menubar: {
            default: 'edit insert view format table tools help'
        },
        otherProps: {
            default: ''
        },
        baseURL: {
            type: String,
            default: 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.0.14/'
        }
    },
    mounted () {
        tinymce.baseURL = this.baseURL;
        tinymce.init({
    	    selector: `#${this.id}`,
            toolbar: this.toolbar,
            menubar: this.menubar,
            statusbar: false,
            autoresize_on_init: true,
            plugins: [
    		    'link table spellchecker lists autolink hr paste autoresize'
		    ],
            init_instance_callback: (editor) => {
                editor.on('KeyUp', (e) => {
                    this.$emit('input', editor.getContent());
                });
                editor.on('Change', (e) => {
                    this.$emit('input', editor.getContent());
                });
                editor.setContent(this.value);
            }    
        })
    },
    destroyed () {
        tinymce.get(this.id).destroy();
    }
}
</script>