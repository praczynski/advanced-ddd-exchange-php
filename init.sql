CREATE TABLE identities
(
    id SERIAL NOT NULL PRIMARY KEY,
    version INT NOT NULL,
    identity_id_uuid UUID         NOT NULL,
    pesel_value       VARCHAR(11)  NOT NULL,
    first_name_value  VARCHAR(255) NOT NULL,
    surname_value     VARCHAR(255) NOT NULL,
    email_value       VARCHAR(255) NOT NULL
);

CREATE TABLE accounts
(
    account_number UUID         NOT NULL PRIMARY KEY,
    identity_id    UUID         NOT NULL,
    trader_number  VARCHAR(15)  NOT NULL,
    status         VARCHAR(255) NOT NULL
);

CREATE TABLE transactions
(
    transaction_number UUID           NOT NULL PRIMARY KEY,
    account_number     UUID           NOT NULL,
    transaction_type   VARCHAR(255)   NOT NULL,
    fund_value         DECIMAL(19, 2) NOT NULL,
    fund_currency      VARCHAR(3)     NOT NULL,
    transaction_date   TIMESTAMP      NOT NULL,
    CONSTRAINT fk_transaction_account_number FOREIGN KEY (account_number) REFERENCES accounts (account_number)
);

CREATE TABLE wallets
(
    wallet_id      UUID           NOT NULL PRIMARY KEY,
    account_number UUID           NOT NULL,
    fund_value     DECIMAL(19, 2) NOT NULL,
    fund_currency  VARCHAR(3)     NOT NULL,
    CONSTRAINT fk_wallet_account_number FOREIGN KEY (account_number) REFERENCES accounts (account_number)
);

CREATE TABLE quotes
(
    quote_id                            UUID           NOT NULL PRIMARY KEY,
    requester_identity_id               UUID           NOT NULL,
    expiration_date                     TIMESTAMP      NOT NULL,
    best_exchange_rate_currency_to_sell VARCHAR(3)     NOT NULL,
    best_exchange_rate_currency_to_buy  VARCHAR(3)     NOT NULL,
    best_exchange_rate                  DECIMAL(15, 2) NOT NULL,
    to_exchange_value                   DECIMAL(15, 2) NOT NULL,
    to_exchange_currency                VARCHAR(3)     NOT NULL,
    exchanged_value                     DECIMAL(15, 2) NOT NULL,
    exchanged_currency                  VARCHAR(3)     NOT NULL,
    status                              VARCHAR(255)   NOT NULL
);

CREATE TABLE currency_pairs
(
    currency_pair_id UUID           NOT NULL PRIMARY KEY,
    base_currency    VARCHAR(3)     NOT NULL,
    target_currency  VARCHAR(3)     NOT NULL,
    base_rate        DECIMAL(15, 2) NOT NULL,
    adjusted_rate    DECIMAL(15, 2),
    status           VARCHAR(50)    NOT NULL
);
CREATE TABLE negotiations
(
    negotiation_id             UUID           NOT NULL PRIMARY KEY,
    negotiator_identity_id     UUID           NOT NULL,
    operator_id                UUID,
    expiration_date            TIMESTAMP,
    target_currency            VARCHAR(3)     NOT NULL,
    base_currency              VARCHAR(3)     NOT NULL,
    proposed_exchange_amount   DECIMAL(15, 2) NOT NULL,
    proposed_exchange_currency VARCHAR(3)     NOT NULL,
    propose_exchange_rate      DECIMAL(15, 2) NOT NULL,
    base_exchange_rate         DECIMAL(15, 2) NOT NULL,
    difference_in_percentage   DECIMAL(15, 2) NOT NULL,
    status                     varchar(255)
);

CREATE TABLE risk_assessments
(
    risk_assessment_number UUID        NOT NULL PRIMARY KEY,
    negotiator_identity_id UUID        NOT NULL,
    risk_level             VARCHAR(10) NOT NULL
);

CREATE TABLE risk_lines
(
    risk_line_id                    UUID           NOT NULL PRIMARY KEY,
    negotiation_id                  UUID           NOT NULL,
    risk_negotiation_value_amount   DECIMAL(15, 2) NOT NULL,
    risk_negotiation_value_currency CHAR(3)        NOT NULL,
    risk_assessment_number          UUID           NOT NULL,
    CONSTRAINT fk_risk_assessment_number FOREIGN KEY (risk_assessment_number) REFERENCES risk_assessments (risk_assessment_number)
);

CREATE TABLE supported_currencies
(
    supported_currency_id UUID           NOT NULL PRIMARY KEY,
    base_currency         VARCHAR(3)     NOT NULL,
    target_currency       VARCHAR(3)     NOT NULL,
    rate                  DECIMAL(15, 2) NOT NULL,
    status                VARCHAR(255)   NOT NULL
);
CREATE TABLE new_client_promotions
(
    identity_id         UUID    NOT NULL,
    account_activated   BOOLEAN NOT NULL,
    negotiation_created BOOLEAN NOT NULL
);

CREATE TABLE promotions
(
    promotion_number UUID         NOT NULL PRIMARY KEY,
    identity_id      UUID         NOT NULL,
    promotion_type   VARCHAR(255) NOT NULL
);

INSERT INTO identities (identity_id_uuid, version, pesel_value, first_name_value, surname_value, email_value) VALUES
                                                                            ('123e4567-e89b-12d3-a456-426655440000', 0,'85070465418', 'Jan', 'Kowalski', 'jan.kowalski@gmail.com'),
                                                                            ('123e4567-e89b-12d3-a456-426655440001', 0, '74062373642', 'Piotr', 'Nowak', 'piotr.nowak@gmail.com'),
                                                                            ('123e4567-e89b-12d3-a456-426655440002', 0, '84032785221', 'Adam', 'Wiśniewski', 'adam.wisniewski@gmail.com');

INSERT INTO accounts (account_number, identity_id, trader_number, status)
VALUES
    ('9c3627e7-d5be-4f7e-8e52-4b7fca97d0a0', '123e4567-e89b-12d3-a456-426655440000', 'ABC-01-2023-123', 'INACTIVE');

INSERT INTO transactions (transaction_number, account_number, transaction_type, fund_value, fund_currency, transaction_date)
VALUES
    ('f49c5c94-3b0e-467c-94c1-2b8356db8cfa', '9c3627e7-d5be-4f7e-8e52-4b7fca97d0a0', 'CARD', 100.00, 'PLN', CURRENT_TIMESTAMP);

INSERT INTO wallets (wallet_id, account_number, fund_value, fund_currency)
VALUES
    ('ea1f5ded-9a48-4083-a7b7-3b526dbf1768', '9c3627e7-d5be-4f7e-8e52-4b7fca97d0a0', 100.00, 'PLN');


INSERT INTO accounts (account_number, identity_id, trader_number, status)
VALUES
    ('3f0f3b8e-6d75-4f61-9c8a-4a64f9e6b267', '123e4567-e89b-12d3-a456-426655440001', 'XYZ-01-2023-123', 'INACTIVE');

INSERT INTO transactions (transaction_number, account_number, transaction_type, fund_value, fund_currency, transaction_date)
VALUES
    ('7f7ccae0-8ad9-4754-a361-b37a87c2d0c8', '3f0f3b8e-6d75-4f61-9c8a-4a64f9e6b267', 'CARD', 500.00, 'PLN', CURRENT_TIMESTAMP);

INSERT INTO wallets (wallet_id, account_number, fund_value, fund_currency)
VALUES
    ('4cd50a81-4bb4-4e2a-bf62-58c8ed51307b', '3f0f3b8e-6d75-4f61-9c8a-4a64f9e6b267', 500.00, 'PLN');


