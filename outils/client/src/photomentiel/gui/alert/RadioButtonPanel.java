package photomentiel.gui.alert;

import java.awt.Component;
import java.awt.FlowLayout;

import javax.swing.ButtonGroup;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.JRadioButton;

/** A simple way to group several radio buttons in a single row, with
 *  a label on the left.
 *  1998-99 Marty Hall, http://www.apl.jhu.edu/~hall/java/
 */

public class RadioButtonPanel extends JPanel {
  public RadioButtonPanel(String labelString, JRadioButton[] radioButtons,  
			  ButtonGroup buttonGroup) {
    setLayout(new FlowLayout(FlowLayout.LEFT));
    add(new JLabel(labelString));
    for(int i=0; i<radioButtons.length; i++) {
      buttonGroup.add(radioButtons[i]);
      add(radioButtons[i]);
    }
  }

  public void setEnabled(boolean state) {
    Component[] components = getComponents();
    for(int i=0; i<components.length; i++)
      components[i].setEnabled(state);
  }
}
