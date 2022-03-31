# Rječnik za računalnu provjeru pravopisa hrvatskoga jezika Hunspellom

Rječnik za računalnu provjeru pravopisa skup je pravila koja omogućuju računalnu provjeru pravopisa hrvatskoga jezika alatom [Hunspell](https://hunspell.github.io/). Rječnik čine dvije datoteke: popis riječi nalazi se u `hr_HR.dic`, a pravila za stvaranje oblika riječi nalazi se u datoteci `hr_HR.aff`.

Sve ostalo je opis projekta, dokumentacija ili alat(i) za njegovo održavanje i ne koristi se za provjeru pravopisa.

Rječnik nije Hunspell i nije njegov dio. Pravila postoje neovisno o njemu, ali i drugim aplikacijama koja za računalnu provjeru pravopisa koriste Hunspell i ova pravila, zato različite aplikacije mogu imati različite inačice istoga rječnika ili pak potpuno različite rječnike: različiti izvori, popisi rječi i pravila za razradu oblika, a bez ikakve međusobne koordinacije. Za ažuriranje rječnika pojedinih aplikacija zaduženi su isključivo održavatelji dodataka pojedine aplikacije ili njezini razvijatelji. Nasreću i nažalost, u slučaju hrvatskoga jezika riječ je o različitim inačicama istoga rječnika.

Izvornu inačicu napravio je Denis Lacković 2003. g.; dostupna je na [cvs.linux.hr/spell](http://cvs.linux.hr/spell/). Dodavati riječi počeo sam 2014. g., ali za ispravljanje, dopunjavanje i unaprjeđenje &ndash; onako kako to izgleda danas &ndash; zaslužan je Mirko Kos (2016.) Većim brojem riječi doprinio je i Boris Jurić (2017.) [^1]

## Sadržaj repozitorija

- `hr_HR.dic` sadrži popis riječi
- `hr_HR.aff` sadrži pravila za razradu oblika riječi iz datoteke `hr_HR.dic`
- `README_hr_HR.txt` sadrži informacije o licenciji[^2]
- `izmjene-u-rječniku.md` prate izmjene u datotekama `hr_HR.dic` i `hr_HR.aff`
- `README.md` upravo čitate
- mapa `tools/dpl` sadrži skriptu kojom sam pokušao automatski izraditi pravila na nekoliko primjera. Daleko od upotrebljivoga. Ako će se nešto po tom pitanju i događati, događat će se u mapi `tools` isključujući `dpl`
- skripta `tools/dictman.php` može generirati sve oblike svih riječi iz rječnika, ali em je `PHP` em rezultat nije detaljno testiran.[^3] Možda bi se na ovome moglo dalje graditi, kad bi se napisalo kako treba
- datoteka `tools/wordlist` rezultat je skripte `dictman.php`
- `tools/rpm/` sadrži skriptu za generiranje `rpm` instalacijskoga paketa (doprinositelj: [asmolcic](https://github.com/asmolcic))

## Kako dodati novu riječ u rječnik

Nažalost, ne postoji automatiziran način dodavanja riječi. S jedne strane zato što nove riječi nema u rječniku pa nema reference za provjeru, a s druge strane zbog velikog broja klasa, kombinacija klasa i broja oblika u klasama, pa automatizirano dodavanje ne može biti precizno i potrebna je ručna provjera.

Nove riječi primam na `kruno.se` na domeni `gmx com`, ali bilo bi dobro kada bi naslov takve poruke bio "Rječnik hr_HR: dodavanje riječi" radi filtriranja i naknadnoga pretraživanja.

Onaj tko ima račun na GitHubu i želi dodati pridjev "riječni", može

- napraviti vlastitu kopiju repozitorija
- otvoriti `hr_HR.dic` i potražiti riječi koje završavaju na -čni
- izvaditi broj iza znaka `/`, u ovom slučaju `353` i `347`
- u `hr_HR.aff` potražiti `# 353` i `# 347` te izvući dvoslovne klase. U prvom slučaju `UM`, u drugome `UO` i `UE`
- pronaći te klase u nastavku `hr_HR.aff` i vidjeti što od toga bolje odgovara
- dodati riječ u `hr_HR.dic` s klasom (brojčanom), sortirati prema abecedi i promijentiti broj na početku te datoteke da odgovara ukupnom broju riječi
- napraviti *Pull Request*

Svjestan sam da bi ove upute mogle više pitanja otvoriti negoli zatvorit, pa ću rado odgovoriti na mejl. Od pomoći može biti i sljedeće:

- [Službena dokumentacija](https://sourceforge.net/projects/hunspell/files/Hunspell/Documentation/hunspell4.pdf/download)
- [hunspell.github.io](http://hunspell.github.io/)

Postoji i nešto što se zove [ProofingToolGui](https://proofingtoolgui.org/) iliti PTG, izgleda prilično dobro, ali nisam radio s time.

[^1]: Ako sam koga izostavio ili želi kakvu izmjenu, može me kontaktirati na `kruno.se` na domeni `gmx com`.

[^2]: Prilikom preuzimanja rječnika, bezobrazno sam promijenio sadržaj datoteke `README_hr_HR.txt`. Sada se u ovoj datoteci nalazi njezin izvorni sadržaj.

[^3]: U tom kontekstu spominju se `unmunch`, `unmunch.sh` te `wordform`. Navodno, ovo posljednje radi s Hunspellom, ali za pojedinu riječ. Možda bi se mogla napraviti skripta koja bi za svaku riječ u rječniku pozivala `wordform`, ali u danom trenutku bilo mi je previše za prožvakati ([github.com/hunspell/issues/404](https://github.com/hunspell/hunspell/issues/404), [hunspell/src/tools/wordforms](https://github.com/hunspell/hunspell/tree/master/src/tools)).



