import javax.crypto.Cipher;
import javax.crypto.SecretKey;
import javax.crypto.spec.IvParameterSpec;
import javax.crypto.spec.SecretKeySpec;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.security.KeyStore;
import java.security.KeyStore.PasswordProtection;
import java.security.KeyStore.ProtectionParameter;
import java.security.KeyStore.SecretKeyEntry;
import java.util.Arrays;
import javax.xml.bind.DatatypeConverter;


public class Config {
	public static String decode(char[] pass, String configKey) {
		try {
			Path path = Paths.get("config.aes");
			KeyStore ks = KeyStore.getInstance("JCEKS");
			FileInputStream fis = null;
			try {
				fis = new FileInputStream("config.keystore");
				ks.load(fis, pass);
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
			SecretKeyEntry keyEnt = (SecretKeyEntry)ks.getEntry("config", new PasswordProtection(pass));
			SecretKey secretKey = keyEnt.getSecretKey();
			byte[] keyWithIv = secretKey.getEncoded(), iv = Arrays.copyOfRange(keyWithIv, 0, 16), key = Arrays.copyOfRange(keyWithIv, 16, keyWithIv.length);
			byte[] ciphertext = DatatypeConverter.parseBase64Binary(DatatypeConverter.printBase64Binary(Files.readAllBytes(path)));
			Cipher c = Cipher.getInstance("AES/CBC/PKCS5Padding");
			SecretKeySpec secret = new SecretKeySpec(key, "AES");
			byte[] result = null;
			c.init(Cipher.DECRYPT_MODE, secret, new IvParameterSpec(iv));
			result = c.doFinal(ciphertext);
			return new String(result);
		} catch (Exception e) {
			e.printStackTrace();
		}
		return null;
	}

	public static void encode(char[] keyStorePath, char[] keyId, char[] pass, char[] pin, String configKey) {
    try {
    	String dataStr = new String(keyStorePath) + "\n" + new String(keyId) + "\n" + new String(pass);
	    KeyStore ks = KeyStore.getInstance("JCEKS");
	    ProtectionParameter protParam = new PasswordProtection(pin);
	    Cipher c = Cipher.getInstance("AES/CBC/PKCS5Padding");
	    byte[] iv = DatatypeConverter.parseHexBinary(configKey.substring(0, 32)), key = DatatypeConverter.parseHexBinary(configKey.substring(32, 64));
	    SecretKeySpec k = new SecretKeySpec(key, "AES");
	    c.init(Cipher.ENCRYPT_MODE, k, new IvParameterSpec(iv));
	    byte[] data = c.doFinal(dataStr.getBytes());
	    FileOutputStream fos = null;
	    try {
        fos = new FileOutputStream("config.aes");
        fos.write(data);
	    } catch (Exception ex) {
        ex.printStackTrace();
	    } finally {
        if (fos != null) {
          fos.close();
        }
	    }
	    SecretKey mySecretKey = new SecretKeySpec(DatatypeConverter.parseHexBinary(configKey), "AES");
	    SecretKeyEntry entry = new SecretKeyEntry(mySecretKey);
	    ks.load(null, pin);
	    ks.setEntry("config", entry, protParam);
	    fos = null;
	    try {
	      fos = new FileOutputStream("config.keystore");
	      ks.store(fos, pin);
	    } catch (Exception ex) {
	        ex.printStackTrace();
	    } finally {
	      if (fos != null) {
	        fos.close();
	      }
	    }
    } catch (Exception e) {
    	e.printStackTrace();
    }
	}
}