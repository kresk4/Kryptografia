public class
specialChars {
	public static char check(String s) {
		char c = '0';
		switch(s) {
			case "C484":
				c = 'Ą';
				break;
			case "C485":
				c = 'ą';
				break;
			case "C486":
				c = 'Ć';
				break;
			case "C487":
				c = 'ć';
				break;
			case "C498":
				c = 'Ę';
				break;
			case "C499":
				c = 'ę';
				break;
			case "C581":
				c = 'Ł';
				break;
			case "C582":
				c = 'ł';
				break;			
			case "C583":
				c = 'Ń';
				break;
			case "C584":
				c = 'ń';
				break;
			case "C393":
				c = 'Ó';
				break;	
			case "C3B3":
				c = 'ó';
				break;
			case "C59A":
				c = 'Ś';
				break;
			case "C59B":
				c = 'ś';
				break;
			case "C5B9":
				c = 'Ź';
				break;
			case "C5BA":
				c = 'ź';
				break;
			case "C5BB":
				c = 'Ż';
				break;
			case "C5BC":
				c = 'ż';
				break;
			default:
				c = '0';
		}
		return c;
	}
}


