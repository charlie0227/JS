mailTo='weicent.cs02@g2.nctu.edu.tw'
mailFrom='Ching-Wei'
mailSubject='Daily Access Info'
echo ${html} | formail -I "From: ${mailFrom}" -I "MIME-Version:1.0" -I "Content-type:text/html;charset=UTF-8" -I "Subject: ${mailSubject}" | sendmail -oi ${mailTo}
