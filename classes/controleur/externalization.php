<?php
//en relation avec lieux:
define("l_region", "list_region");
define("l_dpt_reg", "list_departement_par_region");
define("l_ville_dpt", "list_ville_par_departement");
define("g_ville_from_cp", "get_ville_from_cp");
define("g_ville_from_nom", "get_ville_from_nom");

//en relation avec evenements:
define("g_evt_id", "get_evenement_from_id");
define("gnp_evt_entre_dates", "get_n_premiers_evenements_entre_dates");
define("gnp_evt", "get_n_prochains_evenements");
define("gnp_evt_apres_date", "get_n_premiers_evenements_apres_date");
define("g_evt_date", "get_evenements_date");
define("g_evt_entre_dates", "get_evenements_entre_dates");
define("gnd_evt", "get_n_derniers_evenements");
define("gnd_album", "get_n_derniers_albums");
define("gnd_album_plus", "get_n_derniers_albums_plus");
define("gnd_album_plus_entre_dates", "get_n_derniers_albums_plus_entre_dates");
define("c_album", "create_album");
define("c_evt", "create_evenement");
define("d_album", "delete_album");
define("s_album", "search_album");
define("ss_album", "smart_search_album");
define("s_evt", "search_evenement");
define("ss_evt", "smart_search_evenement");
define("a_m_evt", "add_mail_to_evenement");
define("a_m_album", "add_mail_to_album");

//en relation avec les utilisateurs:
define("logon", "logon");
define("c_usr", "create_utilisateur");
define("u_usr", "update_utilisateur");
define("c_photographe", "create_photographe");
define("u_photographe", "update_photographe");
define("check_email", "check_email");
define("lost_pwd", "lostpwd");
define("s_email_contact", "send_email_contact");
define("s_email_photographe", "send_email_photographe");
define("send_facture", "send_facture");

//en relation avec les stringID
define("g_sid", "get_stringid");
define("g_sid_p_ida", "get_stringid_par_idalbum");

//en relation avec les images
define("gr_image_thumb_path", "get_random_image_thumb_path");

//en relation avec les commandes
define("s_commande", "supprimer_commande");
?>
