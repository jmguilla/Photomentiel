/*
 * Created by JFormDesigner on Sun Sep 05 12:45:50 CEST 2010
 */

package photomentiel.gui;

import java.awt.Container;
import java.awt.Font;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.File;
import java.io.IOException;
import java.io.PipedInputStream;
import java.io.PipedOutputStream;
import java.io.PrintStream;

import javax.swing.GroupLayout;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JComponent;
import javax.swing.JFileChooser;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JPasswordField;
import javax.swing.JProgressBar;
import javax.swing.JScrollPane;
import javax.swing.JTextArea;
import javax.swing.JTextField;
import javax.swing.LayoutStyle;
import javax.swing.SwingConstants;
import javax.swing.SwingUtilities;
import javax.swing.WindowConstants;

import photomentiel.controler.Controler;

import com.jgoodies.forms.factories.DefaultComponentFactory;

/**
 * @author Jean-Michel Guillaume
 */
public class PhotomentielGui  {

	private Controler controler;

	private void buttonConnexionActionPerformed(ActionEvent ae) {
		this.controler.actionPerformed(ae);
	}

	private void buttonFichierActionPerformed(ActionEvent e) {
		this.controler.actionPerformed(e);
	}

	public void initComponents() {
		// JFormDesigner - Component initialization - DO NOT MODIFY  //GEN-BEGIN:initComponents
		// Generated using JFormDesigner Evaluation license - Sophie Leger
		DefaultComponentFactory compFactory = DefaultComponentFactory.getInstance();
		photomentielFrame = new JFrame();
		labelLogin = new JLabel();
		labelPwd = new JLabel();
		tfLogin = new JTextField();
		buttonConnexion = new JButton();
		labelEtatConnexion = new JLabel();
		spConsole = new JScrollPane();
		taConsole = new JTextArea();
		sepConnexion = compFactory.createSeparator("connexion");
		jpPwd = new JPasswordField();
		sepFichier = compFactory.createSeparator("Fichiers");
		sepConsole = compFactory.createSeparator("Console");
		buttonFichier = new JButton();
		labelEtatUpload = new JLabel();
		labelPourcentage = new JLabel();
		pbAvancement = new JProgressBar();
		labelAvancement = new JLabel();
		labelFichierEnCours = new JLabel();
		labelUploadIcon = new JLabel();

		//======== photomentielFrame ========
		{
			photomentielFrame.setTitle("Photomentiel - Photo Uploader");
			photomentielFrame.setDefaultCloseOperation(WindowConstants.EXIT_ON_CLOSE);
			photomentielFrame.setResizable(false);
			Container photomentielFrameContentPane = photomentielFrame.getContentPane();

			//---- labelLogin ----
			labelLogin.setText("login:");

			//---- labelPwd ----
			labelPwd.setText("password:");

			//---- buttonConnexion ----
			buttonConnexion.setText("Se Connecter");
			buttonConnexion.addActionListener(new ActionListener() {
				@Override
				public void actionPerformed(ActionEvent e) {
					buttonConnexionActionPerformed(e);
					buttonConnexionActionPerformed(e);
				}
			});

			//---- labelEtatConnexion ----
			labelEtatConnexion.setText("d\u00e9connect\u00e9");

			//======== spConsole ========
			{

				//---- taConsole ----
				taConsole.setEditable(false);
				spConsole.setViewportView(taConsole);
			}

			//---- buttonFichier ----
			buttonFichier.setText("Choisir Fichiers");
			buttonFichier.setFont(new Font("Tahoma", Font.PLAIN, 16));
			buttonFichier.setToolTipText("Selectionner les fichiers \u00e0 envoyer sur www.photomentiel.fr");
			buttonFichier.addActionListener(new ActionListener() {
				@Override
				public void actionPerformed(ActionEvent e) {
					buttonFichierActionPerformed(e);
				}
			});

			//---- labelEtatUpload ----
			labelEtatUpload.setText("Pas d'upload en cours");
			labelEtatUpload.setFont(new Font("Tahoma", Font.PLAIN, 16));
			labelEtatUpload.setHorizontalAlignment(SwingConstants.CENTER);

			//---- labelPourcentage ----
			labelPourcentage.setText("0/0");
			labelPourcentage.setFont(new Font("Tahoma", Font.PLAIN, 16));
			labelPourcentage.setToolTipText("Avancement");
			labelPourcentage.setHorizontalAlignment(SwingConstants.RIGHT);

			//---- labelAvancement ----
			labelAvancement.setText("Avancement:");

			//---- labelFichierEnCours ----
			labelFichierEnCours.setHorizontalAlignment(SwingConstants.CENTER);

			//---- labelUploadIcon ----
			labelUploadIcon.setHorizontalAlignment(SwingConstants.CENTER);

			GroupLayout photomentielFrameContentPaneLayout = new GroupLayout(photomentielFrameContentPane);
			photomentielFrameContentPane.setLayout(photomentielFrameContentPaneLayout);
			photomentielFrameContentPaneLayout.setHorizontalGroup(
				photomentielFrameContentPaneLayout.createParallelGroup()
					.addGroup(GroupLayout.Alignment.TRAILING, photomentielFrameContentPaneLayout.createSequentialGroup()
						.addContainerGap()
						.addGroup(photomentielFrameContentPaneLayout.createParallelGroup(GroupLayout.Alignment.TRAILING)
							.addComponent(spConsole, GroupLayout.Alignment.LEADING, GroupLayout.DEFAULT_SIZE, 509, Short.MAX_VALUE)
							.addComponent(sepFichier, GroupLayout.Alignment.LEADING, GroupLayout.DEFAULT_SIZE, 509, Short.MAX_VALUE)
							.addComponent(sepConnexion, GroupLayout.Alignment.LEADING, GroupLayout.DEFAULT_SIZE, 509, Short.MAX_VALUE)
							.addGroup(GroupLayout.Alignment.LEADING, photomentielFrameContentPaneLayout.createSequentialGroup()
								.addGroup(photomentielFrameContentPaneLayout.createParallelGroup(GroupLayout.Alignment.LEADING, false)
									.addGroup(photomentielFrameContentPaneLayout.createSequentialGroup()
										.addComponent(labelLogin)
										.addGap(34, 34, 34)
										.addComponent(tfLogin, GroupLayout.PREFERRED_SIZE, 157, GroupLayout.PREFERRED_SIZE))
									.addGroup(photomentielFrameContentPaneLayout.createSequentialGroup()
										.addComponent(labelPwd)
										.addPreferredGap(LayoutStyle.ComponentPlacement.UNRELATED)
										.addComponent(jpPwd)))
								.addGap(33, 33, 33)
								.addGroup(photomentielFrameContentPaneLayout.createParallelGroup(GroupLayout.Alignment.TRAILING)
									.addComponent(labelEtatConnexion, GroupLayout.DEFAULT_SIZE, 251, Short.MAX_VALUE)
									.addComponent(buttonConnexion, GroupLayout.Alignment.LEADING, GroupLayout.DEFAULT_SIZE, 251, Short.MAX_VALUE)))
							.addGroup(GroupLayout.Alignment.LEADING, photomentielFrameContentPaneLayout.createSequentialGroup()
								.addComponent(buttonFichier, GroupLayout.PREFERRED_SIZE, 166, GroupLayout.PREFERRED_SIZE)
								.addPreferredGap(LayoutStyle.ComponentPlacement.RELATED)
								.addGroup(photomentielFrameContentPaneLayout.createParallelGroup()
									.addGroup(photomentielFrameContentPaneLayout.createSequentialGroup()
										.addComponent(labelEtatUpload, GroupLayout.DEFAULT_SIZE, 274, Short.MAX_VALUE)
										.addPreferredGap(LayoutStyle.ComponentPlacement.RELATED)
										.addComponent(labelUploadIcon, GroupLayout.PREFERRED_SIZE, 44, GroupLayout.PREFERRED_SIZE)
										.addGap(8, 8, 8))
									.addComponent(labelFichierEnCours, GroupLayout.DEFAULT_SIZE, 332, Short.MAX_VALUE)))
							.addGroup(GroupLayout.Alignment.LEADING, photomentielFrameContentPaneLayout.createSequentialGroup()
								.addComponent(labelAvancement)
								.addGap(18, 18, 18)
								.addComponent(pbAvancement, GroupLayout.DEFAULT_SIZE, 328, Short.MAX_VALUE)
								.addPreferredGap(LayoutStyle.ComponentPlacement.RELATED)
								.addComponent(labelPourcentage, GroupLayout.PREFERRED_SIZE, 70, GroupLayout.PREFERRED_SIZE))
							.addComponent(sepConsole, GroupLayout.DEFAULT_SIZE, 509, Short.MAX_VALUE))
						.addContainerGap())
			);
			photomentielFrameContentPaneLayout.setVerticalGroup(
				photomentielFrameContentPaneLayout.createParallelGroup()
					.addGroup(photomentielFrameContentPaneLayout.createSequentialGroup()
						.addContainerGap()
						.addComponent(sepConnexion, GroupLayout.PREFERRED_SIZE, GroupLayout.DEFAULT_SIZE, GroupLayout.PREFERRED_SIZE)
						.addPreferredGap(LayoutStyle.ComponentPlacement.RELATED)
						.addGroup(photomentielFrameContentPaneLayout.createParallelGroup(GroupLayout.Alignment.BASELINE)
							.addComponent(labelLogin)
							.addComponent(tfLogin, GroupLayout.PREFERRED_SIZE, GroupLayout.DEFAULT_SIZE, GroupLayout.PREFERRED_SIZE)
							.addComponent(buttonConnexion))
						.addPreferredGap(LayoutStyle.ComponentPlacement.RELATED)
						.addGroup(photomentielFrameContentPaneLayout.createParallelGroup(GroupLayout.Alignment.BASELINE)
							.addComponent(labelPwd)
							.addComponent(jpPwd, GroupLayout.PREFERRED_SIZE, GroupLayout.DEFAULT_SIZE, GroupLayout.PREFERRED_SIZE)
							.addComponent(labelEtatConnexion))
						.addPreferredGap(LayoutStyle.ComponentPlacement.UNRELATED)
						.addComponent(sepFichier, GroupLayout.PREFERRED_SIZE, GroupLayout.DEFAULT_SIZE, GroupLayout.PREFERRED_SIZE)
						.addGap(6, 6, 6)
						.addGroup(photomentielFrameContentPaneLayout.createParallelGroup()
							.addGroup(photomentielFrameContentPaneLayout.createSequentialGroup()
								.addComponent(labelEtatUpload)
								.addPreferredGap(LayoutStyle.ComponentPlacement.UNRELATED)
								.addComponent(labelFichierEnCours, GroupLayout.PREFERRED_SIZE, 24, GroupLayout.PREFERRED_SIZE))
							.addComponent(buttonFichier, GroupLayout.PREFERRED_SIZE, 36, GroupLayout.PREFERRED_SIZE)
							.addComponent(labelUploadIcon, GroupLayout.PREFERRED_SIZE, 18, GroupLayout.PREFERRED_SIZE))
						.addPreferredGap(LayoutStyle.ComponentPlacement.UNRELATED)
						.addGroup(photomentielFrameContentPaneLayout.createParallelGroup()
							.addComponent(pbAvancement, GroupLayout.PREFERRED_SIZE, GroupLayout.DEFAULT_SIZE, GroupLayout.PREFERRED_SIZE)
							.addComponent(labelPourcentage)
							.addComponent(labelAvancement))
						.addPreferredGap(LayoutStyle.ComponentPlacement.RELATED)
						.addComponent(sepConsole, GroupLayout.PREFERRED_SIZE, GroupLayout.DEFAULT_SIZE, GroupLayout.PREFERRED_SIZE)
						.addPreferredGap(LayoutStyle.ComponentPlacement.RELATED)
						.addComponent(spConsole, GroupLayout.DEFAULT_SIZE, 103, Short.MAX_VALUE)
						.addContainerGap())
			);
			photomentielFrame.pack();
			photomentielFrame.setLocationRelativeTo(photomentielFrame.getOwner());
		}
		// JFormDesigner - End of component initialization  //GEN-END:initComponents
	}

	// JFormDesigner - Variables declaration - DO NOT MODIFY  //GEN-BEGIN:variables
	// Generated using JFormDesigner Evaluation license - Sophie Leger
	private JFrame photomentielFrame;
	private JLabel labelLogin;
	private JLabel labelPwd;
	private JTextField tfLogin;
	private JButton buttonConnexion;
	private JLabel labelEtatConnexion;
	private JScrollPane spConsole;
	private JTextArea taConsole;
	private JComponent sepConnexion;
	private JPasswordField jpPwd;
	private JComponent sepFichier;
	private JComponent sepConsole;
	private JButton buttonFichier;
	private JLabel labelEtatUpload;
	private JLabel labelPourcentage;
	private JProgressBar pbAvancement;
	private JLabel labelAvancement;
	private JLabel labelFichierEnCours;
	private JLabel labelUploadIcon;
	// JFormDesigner - End of variables declaration  //GEN-END:variables

	private JFileChooser fc;
	private static final String newline = System.getProperty("line.separator");
    private PipedInputStream piOut;
    private PipedInputStream piErr;
    private PipedOutputStream poOut;
    private PipedOutputStream poErr;

    public void show(){
    	this.setOutputRedirection();
    	this.photomentielFrame.setVisible(true);
    	this.buttonFichier.setEnabled(false);
    	this.photomentielFrame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
    }

    private void setOutputRedirection(){
    	piOut = new PipedInputStream();
        try {
			poOut = new PipedOutputStream(piOut);
		} catch (IOException e) {
			this.logln("Impossible de cr\u00e9er la console convenablement, des informations n'apparaitront pas.");
			this.logln("Cause: " + e.getClass() + " - " + e.getMessage());
		}
        System.setOut(new PrintStream(poOut, true));

        // Set up System.err
        piErr = new PipedInputStream();
        try {
			poErr = new PipedOutputStream(piErr);
		} catch (IOException e) {
			this.logln("Impossible de cr\u00e9er la console convenablement, des informations n'apparaitront pas.");
			this.logln("Cause: " + e.getClass() + " - " + e.getMessage());
		}
        System.setErr(new PrintStream(poErr, true));
        new ReaderThread(piOut).start();
        new ReaderThread(piErr).start();
    }

	public File[] showFileChooser(){
        if (fc == null) {
            fc = new JFileChooser();
            fc.setMultiSelectionEnabled(true);
            fc.addChoosableFileFilter(new ImageFilter());
            fc.setAcceptAllFileFilterUsed(false);
            //Add custom icons for file types.
            //fc.setFileView(new ImageFileView());
            //Add the preview pane.
            fc.setAccessory(new ImagePreview(fc));
        }
        //Show it.
        int returnVal = fc.showDialog(this.photomentielFrame,"Envoyer");

        //Process the results.
        if (returnVal == JFileChooser.APPROVE_OPTION) {
            File[] files = fc.getSelectedFiles();
            fc.setSelectedFile(null);
            return files;
        } else {
        	fc.setSelectedFile(null);
        	return null;
        }
	}

	public String getLogin(){
		return tfLogin.getText();
	}

	public String getPassword(){
		return new String(this.jpPwd.getPassword());
	}

	public void log(String toLog){
		this.taConsole.append(toLog);
	}

	public void logln(String toLog){
		this.taConsole.append(toLog + newline);
	}

	public void connect(){
		this.labelEtatConnexion.setText("Connect\u00e9");
		this.buttonConnexion.setText("Se D\u00e9connecter");
		this.buttonFichier.setEnabled(true);
		this.tfLogin.setEditable(false);
		this.jpPwd.setEditable(false);
	}

	public void setUploadEnCours(){
		this.labelEtatUpload.setText("Upload en cours");
		ImageIcon icon = new ImageIcon(this.getClass().getResource("loading_icon.gif"));
		this.labelUploadIcon.setIcon(icon);
	}

	public void setUploadTermine(){
		this.labelEtatUpload.setText("Pas d'upload en cours");
		this.labelUploadIcon.setIcon(null);
	}

	public void setCurrentUpload(String file){
		if(file.length() > 40){
			file = file.substring(0, 20) + "[...]" + file.substring(file.length()-15);
		}
		this.labelFichierEnCours.setText(file);
	}

	public void connectionEnCours(){
		this.labelEtatConnexion.setText("Connexion en cours...");
	}

	public void disconnect(){
		this.labelEtatConnexion.setText("D\u00e9connect\u00e9");
		this.buttonConnexion.setText("Se Connecter");
		this.buttonFichier.setEnabled(false);
		this.tfLogin.setEditable(true);
		this.jpPwd.setEditable(true);
		this.jpPwd.setText("");
	}

	public void setControler(Controler controler){
		this.controler = controler;
	}

	public void setAvancement(int a){
		this.pbAvancement.setValue(a);
	}

	public void setPourcentage(String pourcentage){
		this.labelPourcentage.setText(pourcentage);
	}

	public void disableUpload(){
		this.buttonFichier.setEnabled(false);
	}

	public void enableUpload(){
		this.buttonFichier.setEnabled(true);
	}

	class ReaderThread extends Thread {
		PipedInputStream pi;
		ReaderThread(PipedInputStream pi) {
			this.pi = pi;
		}
		public void run() {
			try {
				while (true) {
					final int len = pi.read();
					if (len == -1) {
						break;
					}
					SwingUtilities.invokeLater(new Runnable() {
						public void run() {
							taConsole.append(new Character((char)len).toString());

							// Make sure the last line is always visible
							taConsole.setCaretPosition(taConsole.getDocument().getLength());

//							// Keep the text area down to a certain character size
//							int idealSize = 1000;
//							int maxExcess = 500;
//							int excess = taConsole.getDocument().getLength() - idealSize;
//							if (excess >= maxExcess) {
//								taConsole.replaceRange("", 0, excess);
//							}
						}
					});
				}
			} catch (IOException e) {
			}
		}
	}
}