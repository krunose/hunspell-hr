# Izmjene u rječniku


Najnovija se inačica rječnika može preuzeti iz grane `master` repozitorija na adresi [github.com/krunose/hunspell-hr](https://github.com/krunose/hunspell-hr).

### Ažuriranje grane master '30. prosinca 2025 (v. 2.1-20251230)
- dodana riječ 'uskostručan'

### Ažuriranje grane 'master' 29. prosinca 2025 (v. 2.1-20251229)
- dodane riječi: aprosrbirajuće, autentifikacija, interseksusalnost, predmemorija, vjetroelektrana
- dodan REP: tentik (autentikacija) → tentifik (autentifikacija)
- sekcija TRY bazirana na učestalosti pojavljivanja slova, ne prema redoslijedu abecede

### Ažuriranje grane 'master' 30. prosinca 2022.

- ispravak 'ekonomskosocijalni' u 'ekonomsko-socijalni'
- ispravak 'emanentni' u 'imanentni'


### Inačica 2.1-20220323

- dodana riječ `zamrzavati`
- ispravak nevjerojatne bedastoće iz `2.1-201909xx`


### Inačica 2.1-201909xx

- dodana klasa SK za riječi poput 'porodiljni' gdje je pridjev moguć samo u muškome rodu



### Inačica 2.1-20190126

- uklonjeno 74 duplih unosa
- dodano desetak novih klasa
- izbrisano nekoliko riječi s pogreškama u `ije` i `je`
- uklonjeno pedesetak nepostojećih riječi
- dodana pravila za gradove na -vci (Križevci, Vinkovci)
- ispravak zatipaka, ispravci velikih i malih slova i drugi manji ispravci
- proširen REP s šezdesetak novih unosa
- dodano petstotinjak novih riječi
- neke riječi povezane s odgavarajućim klasama


## Inačica 2.1


- izmijenjen ili dodan veći broj riječi
- proširen i sortiran REP do najduljih unosa do najkraćih (pospješava predlaganje)
- proširen BREAK (LO Bugzilla #106989)
- uklonjene kratice s točkom (v. problem br. 231 na [github.com/husnpell/issues](https://github.com/hunspell/hunspell/issues))
- bolji opis projekta (datoteka `README.md`)

---

## Inačica 2.0

veljača 2017.

Strukturna revizija i kompletna izmjene inačice 1.0.

- definiranje novih pravila tvorbe riječi u hr_HR.aff datoteci.
- znatno reduciranje ukupnog broja riječi u hr_HR.dic isključivanjem riječi izvedenih novim pravilima tvorbe iz temeljne riječi.
- proširenje rječnika dodavanjem novih riječi.
- inicijalno testiranje i analiza skupova riječi, usuglašavanje razlika u odnosu na inačicu 1.1

Velika hvala Mirku Kosu koji je pripremio ovu inačicu.


---

## Inačica 1.1

god. 2014.

Nadopuna i nadogradnja inačice 0.1

- dodana kompresija nastavaka (engl. alias compression)
- pretvorba iz ISO8859-2 u UTF-8
- dodane nove riječi
- uklonjena pogreške uzrokovane računalnom obradom liste riječi u inačici 1.0

---

## Inačica 1.0

god. 2003.

Originalni rječnik - autor D. L. iz 2003. g.

[http://cvs.linux.hr/spell/](http://cvs.linux.hr/spell/)
