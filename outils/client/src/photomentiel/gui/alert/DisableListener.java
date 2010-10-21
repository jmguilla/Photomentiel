package photomentiel.gui.alert;

import java.awt.event.ItemEvent;
import java.awt.event.ItemListener;

import javax.swing.AbstractButton;
import javax.swing.JComponent;

/** A listener that you attach to a checkbox or radio button, that,
 *  that toggles the enabled status of some other component when
 *  the entry's state is changed. The defaultState option 
 *  determines if the component associated with the checkbox is
 *  initially enabled (and thus checking the checkbox disables it),
 *  or initially disabled (and thus checking the checkbox enables it).
 *  1998-99 Marty Hall, http://www.apl.jhu.edu/~hall/java/
 */

public class DisableListener implements ItemListener {
  private JComponent target;
  private boolean defaultState;

  private static void addListener(AbstractButton source, JComponent target,
				  boolean defaultState) {
    DisableListener enablerOrDisabler =
      new DisableListener(target, defaultState);
    source.addItemListener(enablerOrDisabler);
  }

  public static void addDisabler(AbstractButton source, JComponent target) {
    addListener(source, target, true);
  }

  public static void addEnabler(AbstractButton source, JComponent target) {
    addListener(source, target, false);
  }

  public DisableListener(JComponent target, boolean defaultState) {
    this.target = target;
    this.defaultState = defaultState;
    if (!defaultState)
      target.setEnabled(false);
  }

  public void itemStateChanged(ItemEvent event) {
    if (event.getStateChange() == ItemEvent.SELECTED)
      target.setEnabled(!defaultState);
    else
      target.setEnabled(defaultState);
  }
}
