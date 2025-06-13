<p align="center"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="PsiConecta Logo"></p>

# PsiConecta

## Sobre o PsiConecta

O PsiConecta é um sistema web inovador desenvolvido para otimizar a gestão de clínicas psicológicas. Nossa plataforma oferece uma solução completa e intuitiva que simplifica o dia a dia de profissionais da saúde mental e seus pacientes.

### Principais Funcionalidades

- **Gestão de Agendamentos**
  - Agendamento online de consultas
  - Confirmações automáticas
  - Lembretes por e-mail/SMS

- **Prontuário Eletrônico**
  - Registro seguro de informações
  - Histórico completo do paciente
  - Evolução do tratamento

- **Gestão Financeira**
  - Controle de pagamentos
  - Relatórios financeiros
  - Integração com sistemas de pagamento

- **Comunicação**
  - Chat seguro entre profissional e paciente
  - Compartilhamento de documentos
  - Notificações importantes

### Benefícios

#### Para Clínicas
- Otimização de processos administrativos
- Redução de custos operacionais
- Gestão centralizada de múltiplos profissionais

#### Para Psicólogos
- Mais tempo para atendimentos
- Organização eficiente da agenda
- Acesso rápido às informações dos pacientes

#### Para Pacientes
- Facilidade no agendamento
- Acesso ao histórico de consultas
- Comunicação direta com o profissional

## Tecnologias

O PsiConecta é desenvolvido utilizando tecnologias modernas e seguras:

- Laravel Framework
- MySQL
- Bootstrap
- JavaScript

## Requisitos do Sistema

Antes de começar, certifique-se que seu sistema atende aos seguintes requisitos:

- PHP >= 8.1
- MySQL >= 5.7
- Composer (Gerenciador de dependências PHP)

## Instalação

Siga estes passos para configurar o projeto em seu ambiente local:

1. Clone o repositório:
   ```bash
   git clone https://github.com/seu-usuario/psiconecta.git
   cd psiconecta
   ```

2. Instale as dependências do PHP:
   ```bash
   composer install
   ```

3. Configure o ambiente:
   - Copie o arquivo `.env.example` para `.env`:
     ```bash
     cp .env.example .env
     ```
   - Gere a chave da aplicação:
     ```bash
     php artisan key:generate
     ```
   - Configure as variáveis de ambiente no arquivo `.env`:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=psiconecta
     DB_USERNAME=seu_usuario
     DB_PASSWORD=sua_senha
     ```

4. Crie o banco de dados:
   - Crie um banco de dados MySQL com o nome configurado em `DB_DATABASE`
   - Execute as migrations:
     ```bash
     php artisan migrate
     ```
   - (Opcional) Execute os seeders para dados de exemplo:
     ```bash
     php artisan db:seed
     ```

5. Inicie o servidor local:
   ```bash
   php artisan serve
   ```

Agora você pode acessar o projeto em `http://localhost:8000`

### Problemas Comuns

- Se encontrar erros de permissão:
  ```bash
  chmod -R 777 storage bootstrap/cache
  ```

- Se as alterações no .env não surtirem efeito:
  ```bash
  php artisan config:clear
  php artisan cache:clear
  ```

