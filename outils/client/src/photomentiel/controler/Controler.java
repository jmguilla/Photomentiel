package photomentiel.controler;

import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Collections;

import javax.swing.SwingUtilities;

import photomentiel.gui.PhotomentielGui;

public class Controler implements ActionListener{

	private final PhotomentielGui gui;
	private String stringID;
	private PhotoUploader uploader;
	private static final String newline = System.getProperty("line.separator");

	public Controler(PhotomentielGui gui,String sid){
		this.gui = gui;
		this.stringID =sid;
		gui.initComponents(this);
	}

	@Override
	public void actionPerformed(ActionEvent ae) {
		if(ae.getActionCommand().equals("se connecter")){
			String login = this.gui.getLogin();
			String pwd = this.gui.getPassword();
			if(this.uploader == null){
				try {
					this.uploader = new PhotoUploader(login, pwd, this.stringID);
					this.gui.connect();
				} catch (Exception e) {
					this.gui.log("Impossible de se connecter" + newline);
					this.gui.log("Cause: " + e.getClass() + " - " + e.getMessage());
				}
			}
		}else if(ae.getActionCommand().equals("Choisir fichiers")){
			this.gui.disableUpload();
			final File[] files = gui.showFileChooser();
			if(files != null){
				Thread toRun = new Thread(){
					public void run(){
						ArrayList<File> tmp = new ArrayList<File>();
						Collections.addAll(tmp, files);
						int total = tmp.size();
						int partial = 0;
						while(tmp.size() > 0){
							File file = tmp.remove(0);
							gui.setCurrentUpload(file.getName());
							try{
								if(!uploader.upload(file)){
									gui.logln("Impossible d'uploader le fichier: " + file.getName());
								}
							}catch(Exception e){
								gui.logln("Impossible d'uploader le fichier: " + file.getName());
								gui.logln("Cause: " + e.getClass());
							}
							partial++;
							gui.setPourcentage(String.valueOf(partial) + "/" + total);
							gui.setAvancement((int)((((double)partial)/total) * 100));
						}
						gui.logln("Upload termin\u00e9.");
						gui.enableUpload();
					}
				};
				toRun.start();
			}else{
				this.gui.logln("Aucun fichier choisi.");
			}
		}else if(ae.getActionCommand().equals("se d\u00e9connecter")){
			if(this.uploader != null && this.uploader.isConnected()){
				try {
					this.uploader.disconnect();
				} catch (IOException e) {
					this.gui.logln("Impossible de se d\u00e9connecter");
					this.gui.logln("Cause: " + e.getClass() + " - " + e.getMessage());
				}
				gui.disconnect();
			}
		}
	}

	/**
     * Create the GUI and show it.  For thread safety,
     * this method should be invoked from the
     * event dispatch thread.
     */
    private static void createAndShowGUI(String sid) {
    	PhotomentielGui gui = new PhotomentielGui();
    	Controler controler = new Controler(gui, sid);
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
