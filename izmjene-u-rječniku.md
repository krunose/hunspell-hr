# Izmjene u rječniku


Najnovija se inačica rječnika može preuzeti iz grane `master` repozitorija na adresi [github.com/krunose/hunspell-hr](https://github.com/krunose/hunspell-hr).

### Ažuriranje grane 'master' 9. rujna 2026.
- dodan REP: reko > rekao, prihvatam > prihvaćam, sedim > sjedim, oćeš > hoćeš, peva > pjeva, texas > Teksas
- dodano: židov (pripadnik vjere) i Židov (pripadnik naroda), isto: židovka i Židovka
- dodana deklinacija na riječ 'DNK'

### Ažuriranje grane 'master' 8. rujna 2026.
- dodan REP za supelemntan > supelementaran, jel > jer, ajde > hajde, gosp > gosp., al > ali, takođe > također, samnom > sa mnom, obećajem > obećavam, ajmo > hajmo (hajdemo), nebi > ne bi(h), ubistvo > ubojstvo, juče > jučer, dodji > dođi, nemogu > ne mogu, pokušaj s REP-om da se predlaže odvojeno 'ne' od svih glagola (zato stavljeno na kraj, da prvo pokuša sve druge oblike zamjene, a tek onda ovo općenito jer bi potencijalno moglo utjecati na prijedloge drugih grešaka s 'ne-' poput 'nestajući'), dodan REP neče > neće, iko > itko, Texas > Teksas, pica > pizza, dobijaš > dobivaš
- dodane riječi: bolonjez, općeuporaban, keksić, neunošenje, AI, zaokupiran, pizza, metapodatak, jebiga, jebemti, jebemu, dodane kratice za mjesece
- brisano 'rđa'
- izmjena: Židov > židov >> piše se i Židov (narodnost) i 'židov' (vjeroispovijest). Ako se u rječnik unese malim slovom, prihvaćat će i 'Židov' i 'židov'. Treba paziti na takve riječi. Ispravak 'lizi' > 'ligi'

### Ažuriranje grane 'master' 7. rujna 2026.
- dodane riječi 'potforum', 'Google', 'guglati', 'A-kategorija', 'ortopan', 'spirometrija', 'bogati' (čestica; kao 'bogamu' itd.), 'boktepitaj', dodana kratica 'dr.', 'bulimičar', 'bulimičarka', 'FIFA', 'multikulturalni', 'karting'
- ispravak: azobenzen > azo-benzen, 'bezvrijedniji' > 'bezvrjedniji', 'binarnodecimalni' > 'binarno-decimalni', brisano 'biserli', brisano 'bljesnut' brisano 'bolešljiv', brisano 'bombonjera', brisano 'božanskiji' jer ta riječ nema komparativ, izbrisan dupli unos 'bučnica/360', dodana opcija WARN na 'bursa', 'bus', brisano 'caklo', brisano 'casino', dodana sklonidba na 'CEFTA'

### Ažuriranje grane 'master' 6. rujna 2026
- dodane riječi 'Hormuz', 'hormuški', 'Washington', 'vašingtonski', 'fizijatar', 'fizijatrija', 'Wikipedija', 'Wikimedija', 'superinteligentni'

### Ažuriranje garane 'master' 5. rujna 2026
- ispravak riječi 'afinitetan/357' u 'afinitetan/359'
- dodane riječi 'bend' i 'Lika'

### Ažuriranje grane 'master' 4. rujna 2026.
- dodano više novih riječi
- dodan REP lter ltar (filtera -> filtara), REP lađ latk (glađi -> glatkiji)
- ispravak riječi 'euro', 'elektrootporan', 'vodootporan'

### Ažuriranje grane 'master' 3. rujna 2026.
- ispravak 'najvrijedniji' u 'najvrjedniji'
- dodana kratica itd.

### Ažuriranje grane 'master' 13. srpnja 2026. (v. 2.1-20260713)
- dodan WORDCHARS . radi rješavanja pitanja kratica s točkom
- dodana hrpa novih kratica

### Ažuriranje grane 'master' 12. srpnja 2026. (v. 2.1-20260712)
- dodana riječ 'šutjeti'
- dodana riječ 'vjetriti'


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
