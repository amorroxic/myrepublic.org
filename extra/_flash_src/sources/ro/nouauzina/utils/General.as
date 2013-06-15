package ro.nouauzina.utils {
 	
	public class General {
					
		public static function emailValid(emailString:String):Boolean{
		
			var emailBool:Boolean = true;
		
			if(emailString.indexOf("@") == -1 || emailString.indexOf("@") == 0){ //not present or first
				emailBool = false;
			}else{
			
				emailBool = true;//if @ exists check for dot
			
				if(emailString.lastIndexOf(".") == -1 || emailString.lastIndexOf(".") == emailString.length-1){ //not present or at very end
				emailBool = false;
			
			}else{
			
				emailBool = true;//if both are true check @ preceeds dot
				if(emailString.lastIndexOf(".") < emailString.indexOf("@")  || emailString.lastIndexOf(".") == emailString.indexOf("@")+1 ){//not before and not directly after
				emailBool = false;
			
			}else{
			
				emailBool = true;
				//lastly check it has NO spaces in it
				for(var i:Number = 0 ; i < emailString.length ; i++){ 
					if(emailString.charAt(i) == " "){ //if the character is a space
					emailBool = false;
				}
			}
			
			}
			
			}
			
			}
			
			
			return emailBool;
		
		}//checkEmail
				

		public static function toCamelCase(TEXT:String):String{
			TEXT = TEXT.toLowerCase();
			var words = TEXT.split(" ");
			for(var i=0;i<words.length; i++){
				words[i] = words[i].charAt(0).toUpperCase() + words[i].substring(1);
			}
			TEXT = words.join(" ");
			return TEXT;
		}
							
	}

}