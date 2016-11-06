import javax.crypto.Cipher;
import javax.crypto.SecretKey;
import javax.crypto.spec.GCMParameterSpec;
import javax.crypto.spec.IvParameterSpec;
import javax.crypto.spec.SecretKeySpec;
import java.io.Console;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.NoSuchFileException;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.security.KeyStore;
import java.security.KeyStore.PasswordProtection;
import java.security.KeyStore.ProtectionParameter;
import java.security.KeyStore.SecretKeyEntry;
import java.util.Arrays;
import javax.xml.bind.DatatypeConverter;

public class Code {
	public static void encode(String filepath, String keyStoreFile, String keyId, String mode, char pass[]) {
		try {
			Path path = Paths.get(filepath);
			KeyStore ks = KeyStore.getInstance("JCEKS");
			byte[] bytes = Files.readAllBytes(path);
			ProtectionParameter protParam = new PasswordProtection(pass);
			Cipher c = null;
			if (mode.equals("cbc")) {
				c = Cipher.getInstance("AES/CBC/PKCS5Padding");
			} else if (mode.equals("ctr")) {
				c = Cipher.getInstance("AES/CTR/PKCS5Padding");
			} else if (mode.equals("gcm")) {
				c = Cipher.getInstance("AES/GCM/PKCS5Padding");
			} else {
				return;
			}
			String myKey = Chars.makeKey();
			byte[] iv = DatatypeConverter.parseHexBinary(myKey.substring(0, 32)), key = DatatypeConverter.parseHexBinary(myKey.substring(32, 64));
			SecretKeySpec k = new SecretKeySpec(key, "AES");
			if (!mode.equals("gcm")) {
				c.init(Cipher.ENCRYPT_MODE, k, new IvParameterSpec(iv));
			} else {
				c.init(Cipher.ENCRYPT_MODE, k, new GCMParameterSpec(128, iv));
			}
			byte[] data = c.doFinal(bytes);
			FileOutputStream fos = null;
			try {
				fos = new FileOutputStream(filepath+".aes");
				fos.write(data);
			} catch (Exception ex) {
				ex.printStackTrace();
			} finally {
				if (fos != null) {
					fos.close();
				}
			}

			SecretKey mySecretKey = new SecretKeySpec(DatatypeConverter.parseHexBinary(myKey), "AES");
			SecretKeyEntry entry = new SecretKeyEntry(mySecretKey);
			ks.load(null, pass);
			ks.setEntry(keyId, entry, protParam);

			fos = null;
			try {
				fos = new FileOutputStream(keyStoreFile);
				ks.store(fos, pass);
				System.out.println("File encrypted successfully. Its name is: " + filepath + ".aes.\n");
			} catch (Exception ex) {
				ex.printStackTrace();
			} finally {
				if (fos != null) {
					fos.close();
				}
			}
		} catch (Exception ex) {
			ex.printStackTrace();
		}
	}
	public static void decode(String filepath, String keyStoreFile, String keyId, String mode, char pass[]) {
		try {
			Path path = Paths.get(filepath);
			KeyStore ks = KeyStore.getInstance("JCEKS");
			FileInputStream fis = null;
			try {
				fis = new FileInputStream(keyStoreFile);
				ks.load(fis, pass);
			} catch (FileNotFoundException e) {
				System.err.println("File "+keyStoreFile+" does not exist!\n");
				System.exit(0);
			} catch (IOException e) {
				System.err.println("Incorrect password!\n");
				System.exit(0);
			} catch (Exception ex) {
				ex.printStackTrace();
			} finally {
				if (fis != null) {
					fis.close();
				}
			}
			SecretKeyEntry keyEnt = (SecretKeyEntry)ks.getEntry(keyId, new PasswordProtection(pass));
			SecretKey secretKey = keyEnt.getSecretKey();
			byte[] keyWithIv = secretKey.getEncoded(), iv = Arrays.copyOfRange(keyWithIv, 0, 16), key = Arrays.copyOfRange(keyWithIv, 16, keyWithIv.length);
			try {
				byte[] ciphertext = DatatypeConverter.parseBase64Binary(DatatypeConverter.printBase64Binary(Files.readAllBytes(path)));
				Cipher c = null;
				if (mode.equals("cbc")) {
					c = Cipher.getInstance("AES/CBC/PKCS5Padding");
				} else if (mode.equals("ctr")) {
					c = Cipher.getInstance("AES/CTR/PKCS5Padding");
				} else if (mode.equals("gcm")) {
					c = Cipher.getInstance("AES/GCM/PKCS5Padding");
				}
				SecretKeySpec secret = new SecretKeySpec(key, "AES");
				byte[] result = null;
				if (!mode.equals("gcm")) {
					c.init(Cipher.DECRYPT_MODE, secret, new IvParameterSpec(iv));
				} else {
					c.init(Cipher.DECRYPT_MODE, secret, new GCMParameterSpec(128, iv));
				}
				result = c.doFinal(ciphertext);
				try {
					String newPath = "decrypted_"+filepath.substring(0, filepath.length()-4);
					FileOutputStream fos = null;
					try {
						fos = new FileOutputStream(newPath);
						fos.write(result);
					} catch (Exception ex) {
						ex.printStackTrace();
					} finally {
						if (fos != null) {
							fos.close();
						}
					}
					System.out.println("File decrypted successfully. Its name is: " + newPath + ".\n");
				} catch (Exception ex) {
					ex.printStackTrace();
				}
			} catch (NoSuchFileException e) {
	      System.err.println("File not found!");
			} catch (Exception ex) {
				ex.printStackTrace();
			}
    } catch (Exception ex) {
			ex.printStackTrace();
		}
	}

	public static void main(String[] args) {
		String filepath = args[0], keyStoreFile = args[1], keyId = args[2], mode = args[3];

		if (args.length != 5) {
			System.err.println("\nYou have to supply 5 parameters!\n");
			return;
		}
		
		System.out.println();
		Console console = System.console();
    char[] pass = new String(console.readPassword("Please enter your password: ")).toCharArray();
    System.out.println("Password typed.\n");
		
		if (args[4].equals("decode")) {
			decode(filepath, keyStoreFile, keyId, mode, pass);
		} else if (args[4].equals("encode")) {
			encode(filepath, keyStoreFile, keyId, mode, pass);
		}
	}
}