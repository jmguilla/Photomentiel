package photomentiel.gui.alert;

import java.awt.Container;
import java.awt.FlowLayout;
import java.awt.GridLayout;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.util.StringTokenizer;

import javax.swing.AbstractButton;
import javax.swing.ButtonGroup;
import javax.swing.JButton;
import javax.swing.JCheckBox;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JRadioButton;

/** Example to let user interactively create any of the main JOptionPanes,
 *  specifying the various options and choosing the desired looks.
 *  1998-99 Marty Hall, http://www.apl.jhu.edu/~hall/java/
 */

public class JOptionPaneAlert extends JFrame implements ActionListener {
  public static void main(String[] args) {
    new JOptionPaneAlert(); 
  }

  private JButton popupDialogButton;
  private JRadioButton[] dialogTypeButtons;
  private JRadioButton[] messageTypeButtons;
  private int[] messageTypes =
    { JOptionPane.PLAIN_MESSAGE, JOptionPane.INFORMATION_MESSAGE, 
      JOptionPane.QUESTION_MESSAGE, JOptionPane.WARNING_MESSAGE, 
      JOptionPane.ERROR_MESSAGE };
  private JLabeledTextField titleField, messageField, selectionValuesField, 
                            buttonLabelsField, focusedButtonField;
  private JCheckBox ignoreSelectionValuesBox;
  private ButtonGroup messageTypeButtonGroup, buttonTypeButtonGroup,
                      dialogTypeButtonGroup;
  
  private JRadioButton[] buttonTypeButtons;
  private int[] buttonTypes =
    { JOptionPane.DEFAULT_OPTION, JOptionPane.YES_NO_OPTION, 
      JOptionPane.YES_NO_CANCEL_OPTION, JOptionPane.OK_CANCEL_OPTION };
  
  private JCheckBox[] lookAndFeelBoxes;


  public JOptionPaneAlert() {
    super("JOptionPane Examples");
    addWindowListener(new ExitListener());
    WindowUtilities.setNativeLookAndFeel();
    Container content = getContentPane();
    content.setLayout(new GridLayout(0, 1));

    JPanel buttonPanel = new JPanel();
    popupDialogButton = new JButton("Show Option Pane(s)");
    popupDialogButton.addActionListener(this);
    buttonPanel.add(popupDialogButton);
    content.add(buttonPanel);
    dialogTypeButtonGroup = new ButtonGroup();
    dialogTypeButtons = 
      new JRadioButton[] { new JRadioButton("Show Message", true),
                           new JRadioButton("Get Confirmation"),
			   new JRadioButton("Collect Input"),
			   new JRadioButton("Present Options") };
    content.add(new RadioButtonPanel("Dialog type:", dialogTypeButtons, 
				     dialogTypeButtonGroup));

    messageTypeButtonGroup = new ButtonGroup();
    messageTypeButtons =
      new JRadioButton[] { new JRadioButton("Plain"), 
			   new JRadioButton("Information", true),
			   new JRadioButton("Question"), 
			   new JRadioButton("Warning"), 
			   new JRadioButton("Error") };
    content.add(new RadioButtonPanel("Option Pane Type:", messageTypeButtons, 
				     messageTypeButtonGroup));

    lookAndFeelBoxes = 
      new JCheckBox[] { new JCheckBox("Native", true), new JCheckBox("Java (Metal)"),
			new JCheckBox("Motif") };
    content.add(new CheckBoxPanel("Looks to use:", lookAndFeelBoxes));

    titleField = 
      new JLabeledTextField("Title:", "Title to appear at top of border");
    content.add(titleField);
    messageField = 
      new JLabeledTextField("Message:", "Message to appear inside of dialog box");
    content.add(messageField);

    buttonTypeButtonGroup = new ButtonGroup();
    buttonTypeButtons =
      new JRadioButton[] { new JRadioButton("Default", true), 
			   new JRadioButton("Yes/No"), 
			   new JRadioButton("Yes/No/Cancel"), 
			   new JRadioButton("OK/Cancel") };
    RadioButtonPanel buttonLabelPanel =
      new RadioButtonPanel("Button Labels:", buttonTypeButtons, 
			   buttonTypeButtonGroup);
    DisableListener.addEnabler(dialogTypeButtons[1], buttonLabelPanel);
    content.add(buttonLabelPanel);

    selectionValuesField = 
      new JLabeledTextField("Choices to present to user (separate by spaces):",
			    "Choice1 Choice2 Choice3 Choice4");
    content.add(selectionValuesField);

    ignoreSelectionValuesBox = 
      new JCheckBox("Ignore predefined choices and supply textfield to user");
    DisableListener.addDisabler(ignoreSelectionValuesBox, selectionValuesField);   
    DisableListener.addEnabler(dialogTypeButtons[2], selectionValuesField);
    DisableListener.addEnabler(dialogTypeButtons[2], ignoreSelectionValuesBox);
    content.add(ignoreSelectionValuesBox);

    buttonLabelsField =
      new JLabeledTextField("Button labels for \"Option\" dialog:",
			    "Button1 Button2 Button3");
    DisableListener.addEnabler(dialogTypeButtons[3], buttonLabelsField); 
    content.add(buttonLabelsField);
    
    pack();
    setVisible(true);
  }

  public void actionPerformed(ActionEvent event) {
    if (lookAndFeelBoxes[0].isSelected()) {
      WindowUtilities.setNativeLookAndFeel();
      popUpDialog();
    } 
    if (lookAndFeelBoxes[1].isSelected()) {
      WindowUtilities.setJavaLookAndFeel();
      popUpDialog();
      WindowUtilities.setNativeLookAndFeel();
    } 
    if (lookAndFeelBoxes[2].isSelected()) {
      WindowUtilities.setMotifLookAndFeel();
      popUpDialog();
      WindowUtilities.setNativeLookAndFeel();
    } 
  }

  private void popUpDialog() {
    if (dialogTypeButtons[0].isSelected())
      JOptionPane.showMessageDialog(this, messageField.getText(),
				    titleField.getText(), getMessageType());
    else if (dialogTypeButtons[1].isSelected())
      JOptionPane.showConfirmDialog(this, messageField.getText(),
				    titleField.getText(), getButtonType(),
				    getMessageType());
    else if (dialogTypeButtons[2].isSelected()) {
      String[] selections;
      if (selectionValuesField.isEnabled())
        selections = substrings(selectionValuesField.getText());
      else
        selections = null;
      JOptionPane.showInputDialog(this, messageField.getText(),
				  titleField.getText(), getMessageType(),
				  null, selections, null);
    } else if (dialogTypeButtons[3].isSelected())
      JOptionPane.showOptionDialog(this, messageField.getText(),
				   titleField.getText(), getButtonType(),
				   getMessageType(), null,
				   substrings(buttonLabelsField.getText()),
				   null);
  }
   
  private int getAssociatedType(AbstractButton[] buttons, int[] types) {
    for(int i=0; i<buttons.length; i++)
      if (buttons[i].isSelected())
        return(types[i]); 
    return(types[0]);
  }

  private int getMessageType() {
    return(getAssociatedType(messageTypeButtons, messageTypes));
  }
    
  private int getButtonType() {
    return(getAssociatedType(buttonTypeButtons, buttonTypes));
  }

  private String[] substrings(String string) {
    StringTokenizer tok = new StringTokenizer(string);
    String[] substrings = new String[tok.countTokens()];
    for(int i=0; i<substrings.length; i++)
      substrings[i] = tok.nextToken();
    return(substrings);
  }
}

class CheckBoxPanel extends JPanel {
  public CheckBoxPanel(String labelString, JCheckBox[] checkBoxes) {
    setLayout(new FlowLayout(FlowLayout.LEFT));
    add(new JLabel(labelString));
    for(int i=0; i<checkBoxes.length; i++) {
      add(checkBoxes[i]);
    }
  }
}
