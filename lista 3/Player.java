import jaco.mp3.player.MP3Player;
import java.io.Console;
import java.io.File;

public class Player {
  private static String configKey = "2a02fa61aa648da95ac29d6a41c43f1b9c3dea7e3ea8ecd131241873b28a4d34";
  public static void main(String[] args) {
    File f = new File("config.aes");
    Console console = System.console();
    System.out.println("\nWelcome to my small MP3 Player!");
    if(f.exists() && !f.isDirectory()) {
      char[] pin = new String(console.readPassword("Please enter your PIN: ")).toCharArray();
      String[] config = Config.decode(pin, configKey).split("\\r?\\n");
      String mp3file = new String(console.readLine("Please select an encrypted .mp3 file you want to play: "));
      Code.decode(mp3file, config[0], config[1], "cbc", config[2].toCharArray());
      File ff = new File("decrypted_"+mp3file.substring(0, mp3file.length()-4));
      if(ff.exists() && !ff.isDirectory()) {
        MP3Player player = new MP3Player(ff);
        player.play();
        while (true) {
          if (player.isStopped()) {
            return;
          }
        }
      }
    } else {
      char[]  keyStorePath = new String(console.readLine("Please enter your keystore's path: ")).toCharArray(),
              keyId = new String(console.readLine("Please enter your key's id: ")).toCharArray(),
              password = new String(console.readPassword("Please enter your key password: ")).toCharArray(),
              pin = new String(console.readPassword("Please enter your PIN: ")).toCharArray();
      Config.encode(keyStorePath, keyId, password, pin, configKey);
    }
    System.out.println();
  }
}