# Sistema de Jogos de futebol

Este projeto é um desafio para a TowOut.
Sistema simples para consultar informações sobre jogos de futebol, incluindo últimos jogos, próximos jogos e jogos de um time específico. Ele utiliza a API do [Api Football]([https://www.football-data.org/](https://www.api-football.com)) para obter dados sobre ligas e jogos de futebol de diversas competições.

## Funcionalidades

- **Pesquisar Últimos Jogos**: Exibe os últimos jogos de uma liga selecionada para o ano especificado.
- **Pesquisar Time**: Exibe os jogos de um time específico em uma temporada específica.
- **Próximos Jogos**: Consulte os próximos jogos de uma liga ou time.

## Tecnologias Utilizadas

- PHP
- Bootstrap 5
- API do [Football-Data.org](https://www.api-football.com)
  
## Estrutura de Arquivos

- `index.php`: Página principal do projeto.
- `functions.php`: Funções para obter dados da API, como ligas e jogos.
- `lastgames.php`: Página para pesquisa de últimos jogos por liga e ano.
- `searchTeam.php`: Página para pesquisar jogos de um time específico em uma temporada.
- `config.php`: Arquivo de configuração (não mostrado, mas necessário para armazenar a chave da API).

## Como Rodar o Projeto Localmente

### Requisitos

- PHP 7.4 ou superior
- Composer (opcional, caso queira gerenciar dependências)

### Passos para execução:

1. Clone o repositório:
   ```bash
   git clone https://github.com/Jorge-Risso/Sistema-de-jogos-de-futebol
