package photomentiel.controler;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.io.PrintWriter;
import java.security.NoSuchAlgorithmException;
import java.util.ArrayList;
import java.util.Collections;

import org.apache.commons.net.PrintCommandListener;
import org.apache.commons.net.ftp.FTP;
import org.apache.commons.net.ftp.FTPClient;
import org.apache.commons.net.ftp.FTPReply;

public class PhotoUploader {
	private final String login, mdp;
	private final String server = "ftp.photomentiel.fr";
	//la racine de l'arborescence pour l'upload. s'en suit, le photographe home, et stringID
	private final String root = "/www/pictures";
	private final String photographeHome;
	private final String stringID;
	private FTPClient ftp;

	public PhotoUploader(String log, String mdp, String pHome, String sid) throws IOException, FTPException, NoSuchAlgorithmException{
		this.login = log;
		this.mdp = mdp;
		this.photographeHome = pHome;
		this.stringID = sid;
		this.connect();
	}
	/**
	 * Modifie la liste donnée en parametre, pour chaque fichier bien uploadé, celui-ci est retiré de la liste.
	 * Il est ainsi plus facile de rejouer l'upload en cas d'échec...
	 * @param files à uploader
	 * @throws IOException
	 * @throws NoSuchAlgorithmException
	 */
	public void upload(ArrayList<File> files) throws IOException, NoSuchAlgorithmException {
		if(ftp != null && ftp.isConnected()){
			String remote = this.root + "/" + this.photographeHome + "/" + this.stringID + "/";
			ftp.changeWorkingDirectory(remote);
			int circuitBroker = 5;
			int current = 0;
			while(files.size() > 0 && circuitBroker > 0){
				File file = files.remove(0);
				if(!file.isDirectory()){
					InputStream input = null;
					try {
						String remoteFile = remote + file.getName();
						input = new FileInputStream(file);
						ftp.storeFile(remoteFile, input);
						circuitBroker = 5;
					} catch (FileNotFoundException e) {
						System.err.println("Fichier " + file.getAbsolutePath() + " non trouv\u00e9.");
					}catch (IOException e) {
						System.err.println("Impossible d'uploader le fichier " + file.getAbsolutePath());
						e.printStackTrace();
						if(!this.ftp.isConnected()){
							System.err.println("Ren\u00e9gociation d'une nouvelle connexion...");
							try {
								this.connect();
								circuitBroker--;
								continue;
							} catch (IOException e1) {
								System.err.println("Impossible de se reconnecter.");
								throw e1;
							} catch (NoSuchAlgorithmException e1) {
								System.err.println("Impossible de se reconnecter.");
								throw e1;
							}
						}
					}finally{
						if(input != null){
							try {
								input.close();
							} catch (IOException e) {
							}
						}
					}
				}else{
					System.err.println("Impossible d'uploader le r\u00e9pertoire " + file.getName());
				}
				current++;
			}
			if(circuitBroker <= 0){
				throw new FTPException("Impossible d'uploader la totalit\u00e9 des fichiers");
			}
		}else{
			throw new FTPException("Ne peut pas uploader de fichiers, la connexion n'est pas \u00e9tablie");
		}
	}

	public boolean upload(File file) throws IOException, NoSuchAlgorithmException{
		if(ftp != null && ftp.isConnected()){
			String remote = this.root + "/" + this.photographeHome + "/" + this.stringID + "/";
			ftp.changeWorkingDirectory(remote);
			int circuitBroker = 5;
			while(circuitBroker > 0){
				if(!file.isDirectory()){
					InputStream input = null;
					try {
						String remoteFile = remote + file.getName();
						input = new FileInputStream(file);
						ftp.storeFile(remoteFile, input);
						return true;
					} catch (FileNotFoundException e) {
						System.err.println("Fichier " + file.getAbsolutePath() + " non trouv\u00e9.");
					}catch (IOException e) {
						System.err.println("Impossible d'uploader le fichier " + file.getAbsolutePath());
						e.printStackTrace();
						if(!this.ftp.isConnected()){
							System.err.println("Ren\u00e9gociation d'une nouvelle connexion...");
							try {
								this.connect();
								circuitBroker--;
								continue;
							} catch (IOException e1) {
								System.err.println("Impossible de se reconnecter.");
								throw e1;
							} catch (NoSuchAlgorithmException e1) {
								System.err.println("Impossible de se reconnecter.");
								throw e1;
							}
						}
					}finally{
						if(input != null){
							try {
								input.close();
							} catch (IOException e) {
							}
						}
					}
				}else{
					System.err.println("Impossible d'uploader le r\u00e9pertoire " + file.getName());
				}
			}
			if(circuitBroker <= 0){
				throw new FTPException("Impossible d'uploader la totalit\u00e9 des fichiers");
			}
		}else{
			throw new FTPException("Ne peut pas uploader de fichiers, la connexion n'est pas établie");
		}
		return false;		
	}

	public boolean isConnected(){
		if(this.ftp != null){
			return this.ftp.isConnected();
		}else{
			return false;
		}
	}

	public boolean disconnect() throws IOException{
		if(this.ftp != null && this.ftp.isConnected()){
			this.ftp.disconnect();
			return true;
		}
		return false;
	}

	private void connect() throws IOException, FTPException, NoSuchAlgorithmException{
		ftp = new FTPClient();
		ftp.setRemoteVerificationEnabled(false);
		ftp.addProtocolCommandListener(new PrintCommandListener(
				new PrintWriter(System.out)));
		try {
			ftp.connect(server);
			int reply = ftp.getReplyCode();
			if (!FTPReply.isPositiveCompletion(reply))
			{
				ftp.disconnect();
				System.err.println("Connexion refus\u00e9 à ftp.photomentiel.fr");
				System.exit(1);
			}
			if(!ftp.login(login, mdp)){
				ftp.logout();
				throw new FTPException("Impossible de se connecter à " + server + " avec le login " + login + " et le mot de passe fourni.");
			}
			ftp.setFileType(FTP.BINARY_FILE_TYPE);
			System.out.println("Connect\u00e9 à ftp.photomentiel.fr en tant que " + login);
		} catch (IOException e) {
			ftp.disconnect();
			ftp = null;
			throw e;
		}
	}

	public static void main(String[] args) throws IOException, FTPException, NoSuchAlgorithmException{
		String login = args[0];
		String mdp = args[1];
		String pHome = args[2];
		String sid = args[3];
		String rep = args[4];
		PhotoUploader pu = new PhotoUploader(login, mdp, pHome, sid);
		ArrayList<File> toUpload = new ArrayList<File>();
		Collections.addAll(toUpload, new File(rep).listFiles());
		pu.upload(toUpload);
	}
}