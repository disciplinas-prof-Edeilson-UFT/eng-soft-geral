

<h1> 1.Utilizando Git e Github </h1>

<h2> Neste arquivo teremos uma série de exemplos de execução de comandos básicos do git, bem como a recomendação de vídeos demonstrando de forma prática o que eles fazem. </h2>

<h3> Introdução ao o que é o git </h3>
<p> <a href = "https://www.youtube.com/watch?v=beMnH51P-T4&list=PLmMxPWmzYRGcTabffOwHBORBjtKa2wCXS"> O que é uma ferramenta de controle de versão? </a> </p>
<p> <a href = "https://www.youtube.com/watch?v=s_Jp_ohfBQw&list=PLmMxPWmzYRGcTabffOwHBORBjtKa2wCXS&index=2"> O que é e como funciona o git. </a> </p>

<h3> Configurações iniciais do git </h3>

<p> Ao instalar o git no seu computador, abra o <strong> git bash </strong> e execute os seguintes comandos, um por vez: </p>

````
  git config --global user.name = "Seu Nome"
  git config --global user.email = "Seu Email"
````

<p> Com isso toodas as vezes que você for trabalhar num projeto compartilhado será possível identificar que você fe algo, seja para te perguntar como aquele trecho de código funciona ou para te reportar um bug no que você fez. </p>

<h3> Comandos básicos do git </h3>

<h5> git add </h5>

<p> Se você viu os vídeos acima sabe como e git funciona. Usando o git add você move os arquivos para a <strong> área de preparo </strong>, onde os arquivos serão preparados para serem salvos. Com o comando abaixo você pode fazer isso: </p>

````
  git add .
````

<h3> git commit </h3>
<p> Ao criar um commit você salva aquela versão do arquivo. Isso faz com que haja um ponto de restauração ali que poderá ser usado para retornar àquele ponto do código. </p>

````
  git commit -m "fix: erro de requisição consertado"
````
<p> Acima temos um exemplo de commit. A tag <strong> -m </strong> é passada para que não seja necessário editar o texto no bloco de notas ou editor que você tiver. O préfixo fix indica que o commit se trata de uma correção no código. Há uma <strong> padronização </strong> de como devem ser feitos os commits. </p>
<p> os mais usados são: </p>
<ul>  
  <li> fix para correções </li>
  <li> feat para novas funcionalidades </li>
  <li> refact para refatorações </li>
  <li> revet para reversões de commits</li>
  <li> docs para documentação </li>
</ul>

<h3> git log </h3>

<p> Quando criamos um commit um <strong> hash </strong>, sequência de caracteres que vai ser o identificador do commit é gerado e ligado a ele. Se executarmos o comando abaixo veremos isso: </p>

````
  git log
````

<p> Com isso vemos todos os commits realizados, sendo que na primeira linha temos o hash do commit, e abaixo quem o fez e o que foi feito (para sair do git log execute um ctrl+z). Isso é importante para vermos os autores de cada commit e também para podermos utilizar o hash do git para executar um git reset para reverter um commit.</p>
<p> A flag --oneline pode ser passada após o git log. As informações serão reduzidas aos primeiros caracteres do hash do commit com o título dele. </p>

<h3> git reset </h3>

<p> Caso você eventualmente erre um commit e queira voltar atrás commo se nada tivesse acontecido de errado, você poderá usar um git reset para isso. Isso serve tanto para quando você está num projeto local quanto caso você erre um git push ou git merge, enviando algo para onde não deveria. Para isso execute um git log, identifique para qual commit você deseja voltar, copie o hash do commit e execute: </p>

````
git reset --hard hashDoCommit
````

<p> Assim tudo o que foi feito após o commit escolhido para ser o ponto mais atual será perdido, e o commit escolhido será visto como o último a ser feito. </p>

<h3> git diff </h3>

<p> Caso ao trabalhar numa <strong> branch </strong> você decida que quer saber o que mudou do estado atual para o último commit use um: </p>

````
  git diff
````

<p> Um detalhamento será exibido mostrando linhas adicionadas ou apagadas dos arquivos alterados, arquivos novos ou removidos. Oara verificar a diferença entre dois commits basta utilizar um git log, copiar o hash dos dois commits e usar um: </p>

````
  git diff hashDoPrimeiroCommit hashDoSegundoCommit
````

<p> Para facilitar a visualização da diferença entre commits é interessante usar a flag <strong> --oneline </strong> no git log para que não haja muita informação na tela. </p>

<h3> git branch </h3>

<p> Quando vocês deseja fazer alterações num código mas não quer que um possível erro causado por elas se espalhe pelo software há um meio de fazer isso. Ao criar um branch você pode como que clonar todo o projeto para uma linha isolada, e assim você aplica as alterações nela, e se tudo ocorrer bem você une ela à linhagem que a deu origem.</p>
<p> Num projeto git por padrão você trabalhará numa branch chamada <strong> master </strong>. Esta é a <strong> branch de produção </strong>, ou seja, a branch que contém o software no estado que os usuários veem. Todas as vezes que um software atualiza, essa é a branch de atualização. </p>
<p> Os programadores criam uma branch que se origina na master, chamada <strong> develop </strong>, e essa é a branch que os desenvolvedores terão acesso. Quando a develop está com mudanças significativas e estáveis, ela é unida à master, para que os usuários tenham acesso à mudança. </p>
<p> Os nomes utilizados nas branches  seguem um padrão, e são eles: </p>
<ul>  
  <li> master (branch de produção) </li>
  <li> develop (branch de desenvolvimento) que se origina na master </li>
  <li> feature (branch de uma nova funcionalidade) que se origina na develop </li>
  <li> bugfix (branch de correção de bugs) que se origina na develop </li>
  <li> hotfix (branch de correção de bugs urgentes) que se origina na master </li>
</ul>
<p> Para verificar em qual branch você está no momento basta usar um: </p>

````
  git branch
````

<p> Caso queira criar uma nova branch, você deve, em primeiro lugar, ir para a branch que deve ser copiada. Por exemplo, sempre que eu quiser criar uma branch de feature nova, antes eu devo me mover para a develop, então poderei criar a branch. Com o comando abaixo você pode criar uma nova branch.</p>

````
  git branch nomeDaBranchQueVoceQuerCriar
````

<p> Com o temmpo será necessário <strong> deletar branches </strong>. Para isso você deve sair dela e executar o comando abaixo: </p>

````
    git branch -d nomeDaBranchQueVaiSerDeletada
````

<p> Caso você queira deletar mais de uma ao mesmo tempo, poderá colocar vários nomes de branches após o -d, todos separados por espaço. Nunca delete a master ou a develop, elas  existirão durante todo o projeot de forma permanente. </p>

<h3> git checkout </h3>
<p> Para trocar a branch em que você está você pode usar um: </p>

````
  git  checkout nomeDaBranchQueVoceQuerIr
````

<p> Caso você queira <strong> criar uma nova branch e ir imediatamente para ela </strong> o comando abaixo faz isso: </p>

````
  git checkout -b nomeDaBranchQueVaiSerCriada
````

<p> Se você não quiser usar o comando acima, poderá fazer da forma mais longa, usando o git branch e depois o git checkout. </p>

<h3> git merge </h3>

<p> Após criar uma branch, se deslocar para dentro dela e validar que todas as modificações feitas tiveram o efeito esperado é necessário unir ela à sua branch de origem. Por exemplo, a branch de feature se origina na develop, logo, após você concluir todas as alterações na sua branch, mova-se para a branch de origem (neste caso a develop) e execute o comando abaixo: </p>

````
  git merge nomeDaBranch
````

<p> Assim, para unir a branch de feature à branch develop eu primeiro uso um gitt checkout develop, git merge branchDeFeature e depois um git branch -d branchDeFeature, pois após o merge as alterações da branchDeFeature foram mescladas com a branch develop, pois não há mais a necessidade da branchDeFeture continuar no projeto, então a deletamos.  </p>

<h2> Trabalhando de forma remota </h2>

<p> Até agora vimos que o git tem várias funcionalidades, porém <strong> tudo ainda é local </strong>. Quando você relizar um git commit isso ainda é visível apenas para você.  Quando se trabalha em equipes é necessário que todos tenham acesso à mesma versão do código. Por isso a plataforma git hub possui integração com o git, para que você possa armazenar seu proojeto lá, assim, as alterações que você  faz ficarão disponíveis para outras pessoas. </p>
<p> Para isso crie uma conta no git hub, e então crie uma pasta nova, dando-a o mesmo nome do seu projeto. <strong> Um link ficará em evidência em sua tela </strong>, copie-o e verifique se ele é o da opção de http.</p>
<p> <strong> Ele serve para que você conecte seu projeto local à esta pasta remota </strong>. Assim numa equipe todos terão um ponto de acesso em comum, já que o projeto estará na nuvem. Para que vocÇe conecte seu projeto local à sua pasta remota basta executar o comando abaixo:</p>

````
  git remote add origin linkQueVoceCopiou
````

<p> Origin é o nome da conexão padrãpo do git, mas você poderá criar as suas com outros nomes. Assim você poderá usar os comandos a seguir para <strong> sincronizar o seu projeto local com o que está na nuvem </strong>. </p>

<h3> git push </h3>

<p> Os commits que você realiza são locais, e caso uma outra pessoa deseje ter acesso a <strong> eles seus commits devem ser enviados para o git hub </strong> (ou outra plataforma que permita o mesmo). Para enviar seus commits para a nuvem você deve executar o git push. Quando você tem várias conexões no mesmo projeto você deverá indicar els no comando, mas se houver apenas uma e ela  for a origin o git push irá usar ela sem a necessidade de indicá-la. </p>
<p> Se você estiver <strong> realizando um git push numa branch que existe apenas localmente </strong>mas não no git hub, <strong> o git irá te sugerir um comando que contem um set upstream </strong>. Basta copiar e colar ele, e quando você o executar <strong> ele irá enviar seus commits criando a branch </strong>. O comando é simples: </p>

````
  git push
````

<h3> git pull </h3>

<p> O git push envia seus commits para o git hub, mas ainda é necessário que você possa <strong> obter também as alterações que as outras pessoas enviaram </strong>. Para isso anvegue para a branch que você deseja receber as alterações e execute um git pull: </p>

````
  git pull
````

<p> <strong> Nunca realize um git push antes de um git pull </strong>, principalmente se o git push for forçado, pois você poderá sobrescrever as alterações das outras pessoas naquela branch. Quando se trabalha com compessoas no mesmo projeto é importante realizar um git pull logo ao iniciar suas tarefas para garantir que você está trabalhando na versão mais atual do projeto. </p>
