var messenger = new Vue({
    el:"#messenger",
    data:{
        name:'',
        message:'',
        response:'',
        sendBut:false,
        messenger:true,
        happy:false,
        unhappy:false,
        load:false
    },
    watch:{
        message:function (newMessage,oldMessage) {
            this.response = 'Ожидаю, когда вы закончите печатать...';
            this.debouncedCheckMessage()
        },
        name:function (newName,oldName) {
            this.response = 'Ожидаю, когда вы закончите печатать...';
            this.debouncedCheckName()
        }
    },
    methods:{
        send:function () {
            this.messenger=false;
            this.sendBut=false;
            this.load=true;
            console.log(this.name+' sent message '+this.message);
            axios.post('./php/messenger.php?action=send', 
                {                
                  name:     this.name,
                  message:  this.message,                  
                })
                .then(function (response) {
                    this.messenger.load=false;
                    if (response.data==1){
                        this.messenger.happy=true;
                    } else {
                        this.messenger.unhappy=true;
                    }
                }
                )
                .catch(function (error) {
		  this.messenger.load=false;
		  this.messenger.unhappy=true;
                  console.log(error);
                });
            
        },
        reset:function () {            
            this.message='';
            this.response='';
            this.sendBut=false;
            this.messenger=true;
            this.happy=false;
            this.unhappy=false;
            this.load=false;        
        },
        CheckMessage:function () {
            this.response='';
            var reg = /^([А-Яа-я0-9_\-\s\.\,\:\;\!\?\#\@\%\&\*\+\=\)\(\<\>\/\№\"\~\\]{1,})$/;
            if(reg.test(this.message) == false){                
                this.sendBut=false;
                this.response='<h4 style="color:#FF7373">Прости, но я пониманию только русский язык.</h4>';                                
            } else {
                this.sendBut=true;
            }                      
        },
        CheckName:function () {
            this.response='';
            this.sendBut=false;
            var reg = /^([А-Яа-я_\-\s\.]{1,})$/;
            if(reg.test(this.name) == false){                
                this.response='<h4 style="color:#FF7373">Прости, но я пониманию только русский язык.</h4>';                
            }            
        }
    },
    created:function () {
        this.debouncedCheckMessage = _.debounce(this.CheckMessage, 500);
        this.debouncedCheckName = _.debounce(this.CheckName, 500);
    }
})