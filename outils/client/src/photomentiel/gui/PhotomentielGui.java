/*
 * Created by JFormDesigner on Sun Sep 05 12:45:50 CEST 2010
 */

package photomentiel.gui;

import java.awt.Container;
import java.awt.Font;
import java.awt.event.ActionEvent;
import java.io.File;
import java.io.IOException;
import java.io.PipedInputStream;
import java.io.PipedOutputStream;
import java.io.PrintStream;

import javax.swing.GroupLayout;
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
import javax.swing.SwingUtilities;
import javax.swing.WindowConstants;

import photomentiel.controler.Controler;

import com.jgoodies.forms.factories.DefaultComponentFactory;

/**
 * @author Jean-Michel Guillaume
 */
public class PhotomentielGui  {

	private void buttonConnexionActionPerformed(ActionEvent e) {
		// TODO add your code here
	}

	public void initComponents(Controler model) {
		// JFormDesigner - Component initialization - DO NOT MODIFY  //GEN-BEGIN:initComponents
		// Generated using JFormDesigner Evaluation license - Jean-Michel Guillaume
		DefaultComponentFactory compFactory = DefaultComponentFactory.getInstance();
		photomentielFrame = new JFrame();
		labelLogin = new JLabel();
		labelPwd = new JLabel();
		tfLogin = new JTextField();
		buttonConnexion = new JButton();
		labelConnexion = new JLabel();
		labelEtatConnexion = new JLabel();
		spConsole = new JScrollPane();
		taConsole = new JTextArea();
		sepConnexion = compFactory.createSeparator("connexion");
		jpPwd = new JPasswordField();
		sepFichier = compFactory.createSeparator("Fichiers");
		sepConsole = compFactory.createSeparator("Console");
		buttonFichier = new JButton();
		labelUpEnCours = new JLabel();
		labelPourcentage = new JLabel();
		pbAvancement = new JProgressBar();
		labelAvancement = new JLabel();

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
			buttonConnexion.setText("se connecter");
			buttonConnexion.addActionListener(model);

			//---- labelConnexion ----
			labelConnexion.setText("Etat connexion:");

			//---- labelEtatConnexion ----
			labelEtatConnexion.setText("d\u00e9connect\u00e9");

			//======== spConsole ========
			{

				//---- taConsole ----
				taConsole.setEditable(false);
				spConsole.setViewportView(taConsole);
			}

			//---- buttonFichier ----
			buttonFichier.setText("Choisir r\u00e9pertoire/fichiers");
			buttonFichier.setFont(new Font("Tahoma", Font.PLAIN, 16));
			buttonFichier.setEnabled(false);
			buttonFichier.addActionListener(model);

			//---- labelUpEnCours ----
			labelUpEnCours.setText("Upload en cours:");
			labelUpEnCours.setFont(new Font("Tahoma", Font.PLAIN, 16));

			//---- labelPourcentage ----
			labelPourcentage.setText("0/0");
			labelPourcentage.setFont(new Font("Tahoma", Font.PLAIN, 16));

			//---- labelAvancement ----
			labelAvancement.setText("Avancement:");

			//---- pbAvancement ----
			pbAvancement.setMaximum(100);

			GroupLayout photomentielFrameContentPaneLayout = new GroupLayout(photomentielFrameContentPane);
			photomentielFrameContentPane.setLayout(photomentielFrameContentPaneLayout);
			photomentielFrameContentPaneLayout.setHorizontalGroup(
				photomentielFrameContentPaneLayout.createParallelGroup()
					.addGroup(photomentielFrameContentPaneLayout.createSequentialGroup()
						.addContainerGap()
						.addGroup(photomentielFrameContentPaneLayout.createParallelGroup()
							.addComponent(sepFichier, GroupLayout.DEFAULT_SIZE, 422, Short.MAX_VALUE)
							.addComponent(spConsole, GroupLayout.DEFAULT_SIZE, 422, Short.MAX_VALUE)
							.addComponent(sepConnexion, GroupLayout.DEFAULT_SIZE, 422, Short.MAX_VALUE)
							.addGroup(photomentielFrameContentPaneLayout.createSequentialGroup()
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
								.addGroup(photomentielFrameContentPaneLayout.createParallelGroup()
									.addGroup(GroupLayout.Alignment.TRAILING, photomentielFrameContentPaneLayout.createSequentialGroup()
										.addComponent(labelConnexion)
										.addPreferredGap(LayoutStyle.ComponentPlacement.RELATED, 22, Short.MAX_VALUE)
										.addComponent(labelEtatConnexion, GroupLayout.PREFERRED_SIZE, 74, GroupLayout.PREFERRED_SIZE))
									.addComponent(buttonConnexion, GroupLayout.DEFAULT_SIZE, 172, Short.MAX_VALUE)))
							.addComponent(sepConsole, GroupLayout.DEFAULT_SIZE, 422, Short.MAX_VALUE)
							.addGroup(photomentielFrameContentPaneLayout.createSequentialGroup()
								.addComponent(buttonFichier)
								.addPreferredGap(LayoutStyle.ComponentPlacement.UNRELATED)
								.addComponent(labelUpEnCours)
								.addPreferredGap(LayoutStyle.ComponentPlacement.UNRELATED)
								.addComponent(labelPourcentage, GroupLayout.DEFAULT_SIZE, 68, Short.MAX_VALUE))
							.addGroup(GroupLayout.Alignment.TRAILING, photomentielFrameContentPaneLayout.createSequentialGroup()
								.addComponent(labelAvancement)
								.addPreferredGap(LayoutStyle.ComponentPlacement.RELATED, 17, Short.MAX_VALUE)
								.addComponent(pbAvancement, GroupLayout.PREFERRED_SIZE, 341, GroupLayout.PREFERRED_SIZE)))
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
							.addComponent(labelEtatConnexion)
							.addComponent(labelConnexion))
						.addPreferredGap(LayoutStyle.ComponentPlacement.UNRELATED)
						.addComponent(sepFichier, GroupLayout.PREFERRED_SIZE, GroupLayout.DEFAULT_SIZE, GroupLayout.PREFERRED_SIZE)
						.addPreferredGap(LayoutStyle.ComponentPlacement.RELATED)
						.addGroup(photomentielFrameContentPaneLayout.createParallelGroup(GroupLayout.Alignment.BASELINE)
							.addComponent(buttonFichier, GroupLayout.PREFERRED_SIZE, 36, GroupLayout.PREFERRED_SIZE)
							.addComponent(labelUpEnCours)
							.addComponent(labelPourcentage))
						.addPreferredGap(LayoutStyle.ComponentPlacement.RELATED, 13, Short.MAX_VALUE)
						.addGroup(photomentielFrameContentPaneLayout.createParallelGroup()
							.addComponent(pbAvancement, GroupLayout.PREFERRED_SIZE, GroupLayout.DEFAULT_SIZE, GroupLayout.PREFERRED_SIZE)
							.addComponent(labelAvancement))
						.addPreferredGap(LayoutStyle.ComponentPlacement.RELATED)
						.addComponent(sepConsole, GroupLayout.PREFERRED_SIZE, GroupLayout.DEFAULT_SIZE, GroupLayout.PREFERRED_SIZE)
						.addPreferredGap(LayoutStyle.ComponentPlacement.RELATED)
						.addComponent(spConsole, GroupLayout.PREFERRED_SIZE, 103, GroupLayout.PREFERRED_SIZE)
						.addContainerGap())
			);
			this.setOutputRedirection();
			photomentielFrame.pack();
			photomentielFrame.setLocationRelativeTo(photomentielFrame.getOwner());
			photomentielFrame.setVisible(true);
		}
		// JFormDesigner - End of component initialization  //GEN-END:initComponents
	}

	// JFormDesigner - Variables declaration - DO NOT MODIFY  //GEN-BEGIN:variables
	// Generated using JFormDesigner Evaluation license - Jean-Michel Guillaume
	private JFrame photomentielFrame;
	private JLabel labelLogin;
	private JLabel labelPwd;
	private JTextField tfLogin;
	private JButton buttonConnexion;
	private JLabel labelConnexion;
	private JLabel labelEtatConnexion;
	private JScrollPane spConsole;
	private JTextArea taConsole;
	private JComponent sepConnexion;
	private JPasswordField jpPwd;
	private JComponent sepFichier;
	private JComponent sepConsole;
	private JButton buttonFichier;
	private JLabel labelUpEnCours;
	private JLabel labelPourcentage;
	private JProgressBar pbAvancement;
	private JLabel labelAvancement;
	// JFormDesigner - End of variables declaration  //GEN-END:variables

	private JFileChooser fc;
	private static final String newline = System.getProperty("line.separator");
    private PipedInputStream piOut;
    private PipedInputStream piErr;
    private PipedOutputStream poOut;
    private PipedOutputStream poErr;

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
		this.labelEtatConnexion.setText("connect\u00e9");
		this.buttonConnexion.setText("se d\u00e9connecter");
		this.buttonFichier.setEnabled(true);
		this.tfLogin.setEditable(false);
		this.jpPwd.setEditable(false);
	}

	public void setCurrentUpload(String file){
		if(file.length() > 10){
			file = file.substring(0, 10) + "...";
		}
		this.labelUpEnCours.setText(file);
	}

	public void disconnect(){
		this.labelEtatConnexion.setText("d\u00e9connect\u00e9");
		this.buttonConnexion.setText("se connecter");
		this.buttonFichier.setEnabled(false);
		this.tfLogin.setEditable(true);
		this.jpPwd.setEditable(true);
		this.jpPwd.setText("");
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