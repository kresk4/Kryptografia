import java.util.Random;

public class Chars {
	public static String makeKey() {
		String alphabet = "0123456789abcdef", p = "";
		Random random = new Random();
		for (int i = 0; i < 64; i++) {
			p += alphabet.charAt(random.nextInt(16));
		}
		return p;
	}
}