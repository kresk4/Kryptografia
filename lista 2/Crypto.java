import javax.crypto.Cipher;
import javax.crypto.BadPaddingException;
import javax.crypto.NoSuchPaddingException;
import javax.crypto.spec.IvParameterSpec;
import javax.crypto.spec.SecretKeySpec;
import javax.xml.bind.DatatypeConverter;
import java.security.NoSuchAlgorithmException;
import java.util.Random;
import java.util.ArrayList;
import java.lang.System;

class Run implements Runnable {
	private int id;
	private String s;
	private Cipher cipher = null;
	SecretKeySpec secret = null;
	IvParameterSpec ivParameterSpec = null;

	public Run(int id) {
		this.id = id;
		switch (id) {
			case 0:  s = new String("000000000");
				break;
			case 1:  s = new String("000000002");
				break;
			case 2:  s = new String("000000004");
				break;
			case 3:  s = new String("000000006");
				break;
			case 4:  s = new String("000000008");
				break;
			case 5:  s = new String("00000000a");
				break;
			case 6:  s = new String("00000000c");
				break;
			case 7:  s = new String("00000000e");
				break;
			default: s = new String("000000000");
				break;
		}
		try {
			cipher = Cipher.getInstance("AES/CBC/PKCS5Padding");
		} catch (NoSuchAlgorithmException e) {
			cipher = null;
			e.printStackTrace();
		} catch (NoSuchPaddingException e) {
			e.printStackTrace();
		}
		ivParameterSpec = new IvParameterSpec(Crypto.iv);
	}
	public void run() {
		while (!Crypto.done) {
			if (!Crypto.done) {
				try {
					String key = s + Crypto.suffix;
					byte[] keyBytes = Crypto.toByteArray(key);
					secret = new SecretKeySpec(keyBytes,"AES");
					cipher.init(Cipher.DECRYPT_MODE, secret, ivParameterSpec);
					byte[] result = cipher.doFinal(Crypto.ciphertext);
					String p = DatatypeConverter.printHexBinary(result);
					Crypto.total++;
					if (Crypto.isStringValid(p)) {
						System.out.println("Message found!\n\n"+p);
						Crypto.done = true;
						long timeOut = System.currentTimeMillis();
						System.out.println("\nTime elapsed: " + (timeOut-Crypto.timeStart)/1000 + "s / n = " + Crypto.total + " / key = " + key + "\n");
						return;
					} else {
						s = Crypto.nextSequence(s);
						continue;
					}
				} catch (BadPaddingException ex) {
					s = Crypto.nextSequence(s);
					continue;
				} catch (Exception ex) {
					s = Crypto.nextSequence(s);
					ex.printStackTrace();
					continue;
				}
			} else {
				return;
			}

		}
	}
}


public class Crypto {
	public static boolean done = false;
	public static byte[] iv, ciphertext;
	public static String suffix = "";
	public static String alphabet = "0123456789abcdef";
	public static int n = alphabet.length();
	public static int total;
	public static long timeStart = System.currentTimeMillis();
	public static byte[] toByteArray(String s) {
		return DatatypeConverter.parseHexBinary(s);
	}
	public static String randomChar() {
		Random random = new Random();
		return new Character(alphabet.charAt(random.nextInt(n))).toString();
	}
	
	public static String nextSequence(String sequence) {
		boolean carry = false;
		char[] nextSequence = sequence.toCharArray();

		nextSequence[0] += 1;

		if (nextSequence[0] == 'f' + 1) {
			nextSequence[0] = '0';
			carry = true;

			for(int i = 1; i < sequence.length() && carry; i++) {
				nextSequence[i] += 1;

				if (nextSequence[i] == 'f' + 1) {
					nextSequence[i] = '0';
					carry = true;
				}
				else if(nextSequence[i] == '9' + 1) {
					nextSequence[i] = 'a';
					carry = false;
				}
				else carry = false;
			}
		}
		else if(nextSequence[0] == '9' + 1) {
			nextSequence[0] = 'a';
		}
		return new String(nextSequence);
	}

	public static boolean isStringValid(String s) {
		int i = 0;
		if (s.length() <= 1) {
			return false;
		}
		String result = "";
		while (i < s.length()) {
			String hex = s.substring(i, i+2);
			int h = Integer.parseInt(hex, 16);
			if (h < 128) {
				result += (char)h;
				i += 2;
			} else if (h >= 128 && h <= 255) {
				char c = specialChars.check(s.substring(i, i+4));
				if (c == '0') {
					return false;
				} else {
					result += c;
				}
				i += 4;
			}
		}
		System.out.println("\n" + result.trim());
		return true;
	}
	public static void main (String[] args) throws Exception {
		total = 0;
		int thr = 8;
		if (args.length != 3) {
			System.out.println("\nError! 3 parameters required:\n- iv;\n- suffix of key;\n- ciphertext.\n");
			return;
		}
		iv = toByteArray(args[0]);
		suffix = args[1];
		int len = (int)Math.pow(n, 64-suffix.length());
		ciphertext = DatatypeConverter.parseBase64Binary(args[2]);
		Runnable[] runners = new Runnable[thr];
		Thread[] threads = new Thread[thr];
		for (int i = 0; i < runners.length; i++) {
			runners[i] = new Run(i);
		}
		for (int i = 0; i < runners.length; i++) {
			threads[i] = new Thread(runners[i]);
		}
		for (int i = 0; i < runners.length; i++) {
			threads[i].start();
		}
	}
}