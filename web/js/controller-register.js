/**
 * Created by ayme on 3/7/16.
 */

(function(){
    angular.module('dutings').controller("RegisterCtrl", [function(){
        console.log("In Register");
        var self = {};
        self.name = "";
        self.email = "";
        self.password = "";
        self.repassword = "";
        self.companyName = "";
        self.phoneNumber = "";

        function allFieldsValid(){
            if(self.name.length===0){
                console.log("Please enter a valid name");
                return false;
            }
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if(self.email.length===0 || !re.test(self.email)){
                console.log("Please enter a valid email");
                return false;
            }
            if(self.companyName.length===0){
                console.log("Please enter a valid company name");
                return false;
            }
            if(self.phoneNumber.length===0){
                console.log("Please enter a valid phone number");
                return false;
            }
            if(self.password.length<6){
                console.log("Password must be at least 6 characters");
                return false;
            }
            if(self.repassword.length===0 || self.repassword!==self.password){
                console.log("Password do not match");
                return false;
            }
            return true;
        }
        self.sendConfirmationEmail=function(){
            if(allFieldsValid()){
                console.log("SENDING CONFIRMATION EMAIL");
            }
        };
        return self;
    }]);
})();