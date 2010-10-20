package photomentiel.controler;

import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Timer;
import java.util.TimerTask;

import javax.swing.SwingUtilities;

import photomentiel.gui.PhotomentielGui;

public class Controler implements ActionListener{

	private final Timer timer = new Timer();
	private final PhotomentielGui gui;
	private final String stringID;
	private PhotoUploader uploader;
	private static final String newline = System.getProperty("line.separator");

	public Controler(PhotomentielGui gui,String sid){
		this.gui = gui;
		this.stringID =sid;
	}

	public void actionPerformed(final ActionEvent ae) {
		TimerTask tt = new TimerTask(){
			public void run(){
				if(ae.getActionCommand().equals("Se Connecter")){
					String login = Controler.this.gui.getLogin();
					String pwd = Controler.this.gui.getPassword();
					if(Controler.this.uploader == null || !Controler.this.uploader.isConnected()){
						try {
							Controler.this.gui.connectionEnCours();
							Controler.this.uploader = new PhotoUploader(login, pwd, Controler.this.stringID);
							Controler.this.gui.connect();
						} catch (Exception e) {
							Controler.this.gui.log("Impossible de se connecter" + newline);
							Controler.this.gui.log("Cause: " + e.getClass() + " - " + e.getMessage());
							Controler.this.gui.disconnect();
						}
					}
				}else if(ae.getActionCommand().equals("Choisir Fichiers")){
					Controler.this.gui.disableUpload();
					final ArrayList<File> files = Controler.this.extractPictures(gui.showFileChooser());
					if(files != null){
						Thread toRun = new Thread(){
							public void run(){
								gui.setAvancement(0);
								int total = files.size();
								int partial = 0;
								while(files.size() > 0){
									gui.setUploadEnCours();
									partial++;
									File file = files.remove(0);
									gui.setCurrentUpload(file.getName());
									gui.setPourcentage(String.valueOf(partial) + "/" + total);
									try{
										if(!uploader.upload(file)){
											gui.logln("Impossible d'uploader le fichier: " + file.getName());
										}
									}catch(Exception e){
										gui.logln("Impossible d'uploader le fichier: " + file.getName());
										gui.logln("Cause: " + e.getClass());
									}
									gui.setPourcentage(String.valueOf(partial) + "/" + total);
									gui.setAvancement((int)((((double)partial)/total) * 100));
								}
								gui.setUploadTermine();
								gui.setCurrentUpload("");
								gui.logln("Upload termin\u00e9.");
								gui.enableUpload();
							}
						};
						toRun.start();
					}else{
						Controler.this.gui.logln("Aucun fichier choisi.");
					}
				}else if(ae.getActionCommand().equals("Se D\u00e9connecter")){
					if(Controler.this.uploader != null && Controler.this.uploader.isConnected()){
						try {
							Controler.this.uploader.disconnect();
						} catch (IOException e) {
							Controler.this.gui.logln("Impossible de se d\u00e9connecter");
							Controler.this.gui.logln("Cause: " + e.getClass() + " - " + e.getMessage());
						}
						gui.disconnect();
					}
				}
			}

		};
		this.timer.schedule(tt, 0);
	}

	/**
	 * To be sure that we only have .jpeg in the parameter
	 * @param showFileChooser
	 * @return
	 */
	private ArrayList<File> extractPictures(File[] showFileChooser) {
		ArrayList<File> result = new ArrayList<File>();
		for(File file : showFileChooser){
			if(file.isFile() && file.exists()){
				String name = file.getName();
				int iddot = name.lastIndexOf(".");
				String ext = "";
				if(iddot!=-1){
					ext = name.substring(iddot);
				}
				if(".jpg".equalsIgnoreCase(ext) || ".jpeg".equalsIgnoreCase(ext)){
					result.add(file);
				}
			}else if(file.isDirectory() && file.exists()){
				result.addAll(this.extractPictures(file.listFiles()));
			}
		}
		return result;
	}

	/**
     * Create the GUI and show it.  For thread safety,
     * this method should be invoked from the
     * event dispatch thread.
     */
    private static void createAndShowGUI(String sid) {
    	final PhotomentielGui gui = new PhotomentielGui();
    	final Controler controler = new Controler(gui, sid);
    	gui.setControler(controler);
    	gui.initComponents();
    	gui.show();
    	Runtime.getRuntime().addShutdownHook(new Thread(){
    		public void run(){
    			try {
    				if(controler.uploader != null){
    					controler.uploader.disconnect();
    				}
				} catch (IOException e) {
					e.printStackTrace();
				}
    		}
    	});
    }

	public static void main(String[] args) {
		final String sid = args[0];
        //Schedule a job for the event dispatch thread:
        //creating and showing this application's GUI.
        SwingUtilities.invokeLater(new Runnable() {
            public void run() {
                //Turn off metal's use of bold fonts
                createAndShowGUI(sid);
            }
        });
    }
}
