# Sistema de Registro de Pontos e Gerenciamento de Funcionários

Este é um sistema desenvolvido em PHP utilizando o framework Laravel para gerenciar pontos de funcionários e informações administrativas. Ele implementa a arquitetura **Domain-Driven Design (DDD)**, **Command Query Responsibility Segregation (CQRS)** e é orientado a eventos.

---

## **Requisitos**

### **Tecnologias Necessárias**
- **Docker** e **Docker Compose**
- **Composer** (instalado dentro do container)
- **MySQL** (subido via Docker Compose)

---

## **Passo a Passo para Subir o Ambiente**

### **1. Clone o Repositório**
```bash
git clone https://github.com/Ananiaslitz/ticto-teste
cd ticto-test
docker compose up -d
```

## Disclaimer
Cara, puxado, muito detalhe e eu ainda quis fazer uma graça arquitetural hehe.
Não tive tempo de fazer tudo que gostaria nesse teste, porém dei uma pincelada nos pontos mais importantes que são os principios básicos como SOLID, Testes e tentei mostrar algum conhecimento arquitetural deixando a aplicacao escalavel no sentido de que se quisermos implementar uma replica de leitura, CQRS, Event Sourcing, e até permitir que essa aplicacao seja orientada a eventos esta relativamente pronto para isso.

Espero que gostem :D fiz com muito esforço por conta dos meus filhos e somente depois das 20, como demonstrado na hora dos commits, hehe.
