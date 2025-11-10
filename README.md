# Aplikacija za upravljanje projektov
Verzija: 0.1.5 
Datum: 10. 11. 2025

## Zahteve
- PHP knjižnica MongoDB
- PHP razširitev MongoDB

## Uporaba
1. iz repozitorija PECL prenesi ustrezno verzijo PHP razširitve MongoDB
2. iz repozitorija Composer prenesi ustrezno verzijo PHP knjižnice MongoDB
3. ustvari MongoDB bazo podatkov, ki mora vsebovati:
   - zbirko _users_ z dokumentom s podatki \[name(String): \[ime administratorskega uporabnika po izbiri\], surname(String): \[priimek administratorskega uporabnika po izbiri\], email(String): \[email po izbiri\], password(String): \[geslo po izbiri\], roles(Array): {1}\]
   - zbirko _status_ z dokumenti:
     - \[numericId(Int32): 1, status(String): "Aktiven\]
     - \[numericId(Int32): 2, status(String): "Na čakanju\]
     - \[numericId(Int32): 3, status(String): "Ideja\]
     - \[numericId(Int32): 4, status(String): "Arhiviran\]
   - zbirko _tasks_
4. prenesi datoteke iz repozitorija na strežnik
5. v datoteko _config.php_ prepiši podatke za povezavo na bazo podatkov
6. na strežniku nastavi, da strežnik ob zahtevani mapi s projektom vrne datoteko _login.html_
7. v spletno mesto se prijavi z administratorskim računom, preko katerega ustvari uporabniški račun

## Spremembe
- _v0.1.5_ (10. 11. 2025):
  - popravi čudno napako, zaradi katere se je program zapletel v neskončno zanko v nekaterih primerih izpisa vseh nalog
- _v0.1.4_ (9. 11. 2025):
  - popravi napako, zaradi katere so gumbi za dodajanje podnalog vodili na neobstoječo stran
  - popravi napako, zaradi katere so vse podnaloge imele status aktivnega projekta
- _v0.1.3_ (6. 11. 2025):
  - popravi napako, zaradi katere se spreminjajo prioritete projektov ob dodajanju projektov pod nepovezanimi statusi
- _v0.1.2_ (6. 11. 2025):
  - popravi napako pri razvrščanju projektov
- _v0.1.1_ (6. 11. 2025):
  - popravljeno napačno razvrščanje projektov po statusih
- _v0.1.0_ (5. 11. 2025):
  - prva postavitev projekta
