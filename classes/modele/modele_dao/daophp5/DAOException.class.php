<?php
/*
 * $Id: DAOException.class.php,v 1.2 2005/03/25 03:07:16 au5lander Exp $
 * 
 * This program is free software; you can redistribute it and/or modify  it
 * under the terms of the GNU General Public License as published by  the Free
 * Software Foundation; either version 2 of the License, or  (at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,  but WITHOUT
 * ANY WARRANTY; without even the implied warranty of  MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the  GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License  along with
 * this program; if not, write to the Free Software  Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA  02111-1307  USA
 */
 
/**
 * class: DaoException
 * 
 * general purpose exception class used
 * by daophp5 classes
 */
class DAOException extends Exception {

	// methods

	/**
	 * method: display
	 * 
	 * displays exception information
	 */
	public function display() {
		echo "Error!\n";
		echo "----------------------------------\n";
		echo "Code: ".$this->getCode()."\n";
		echo "File: ".$this->getFile()."\n";
		echo "Line: ".$this->getLine()."\n";
		echo "Message: ".$this->getMessage()."\n";
		echo "----------------------------------\n\n";
	}
}
?>