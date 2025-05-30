[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kasper-bth/mvc-kasper/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kasper-bth/mvc-kasper/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/kasper-bth/mvc-kasper/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/kasper-bth/mvc-kasper/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/kasper-bth/mvc-kasper/badges/build.png?b=master)](https://scrutinizer-ci.com/g/kasper-bth/mvc-kasper/build-status/master)

![En bild på mig](/public/img/jag.png "En bild på mig")

# Detta är mitt github repo för kursen <a href="https://dbwebb.se/kurser/mvc-v2">mvc</a>
Innehållet i detta repo är för kursen mvc. Här finns all kod som jag har skrivit för kursen mvc med olika taggar för att se hur min kodbas har ändrats under kursens gång samt hur kodbasen ser ut för slutet av kursen. Detta är ett symfony projekt som då använder symfony ramverket för att se mer om symfony kan man besöka deras hemsida <a href="https://symfony.com/"></a>.

## Starta upp projektet lokalt

För att klona detta repo kör  `$ git clone https://github.com/kasper-bth/mvc-kasper.git`

För att köra det klonade repot lokalt kör `php -S localhost:8888 -t public` och stå i roten.

## Innehåll i detta repo

- Kod för min report sida.
- Verisioner för varje del av kursmomentets gång.

## Innehåll på hemsidan
- `/` På denna route är det en kort introduktion om mig.
- `/about` På denna route finns det en  introduktion om kursen.
- `/report` På denna route finns min rapport för varje kursmoment.
- `/lucky` På denna route finns en liten mer "crazy" design för en sida där man får ett slumpmässigt nummer mellan 1-100.
- `/card` På denna route finns det flera andra routes som leder till olika delsidor som använder en kortlek för att göra olika saker.
- `/game` På denna route kan man spela kort spelet tjugoett.
- `/library` På denna route kan man använda en databas för att skapa böcker, uppdater böcker, ta bort böcker och se alla böcker.
- `/metrics` På denna route finns det en analys om min kod där jag förbättrade och löste fel med koden.
- `/api` På denna route kan man se alla API routes för kursen det finns många delssidor som man kan ta sig till.
- `/proj` På denna route finns mitt projekt för denna kurs då jag har gjort ett blackjack spel och man blir tagen till en ny design och en egen del för just projektet.

## Annat
- Under `docs` mappen i repot kan man nå index filer för både metrics och coverage där man kan se vad av koden blir testat och analys som genereras av phpmetrics.
- Det finns flera composer scripts man kan köra om man står i roten av projektet. man kan köra `composer phpunit` för att köra tester, `composer phpdoc` för att generera dokumentation, `composer lint` för att kolla hur koden matchar efter linters, `composer csfix` för att fixa till kodstilen och generera metrics genom `composer phpmetrics`. 

Hemsidan för detta projekt ligger på denna hemsida <a href="https://www.student.bth.se/~kahg24/dbwebb-kurser/mvc/me/report/public/">https://www.student.bth.se/~kahg24/dbwebb-kurser/mvc/me/report/public</a>
