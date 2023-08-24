CREATE TABLE identities
(
    id SERIAL NOT NULL PRIMARY KEY,
    version INT,
    identity_id_uuid UUID         NOT NULL,
    pesel_value       VARCHAR(11)  NOT NULL,
    first_name_value  VARCHAR(255) NOT NULL,
    surname_value     VARCHAR(255) NOT NULL,
    email_value       VARCHAR(255) NOT NULL
);

CREATE TABLE accounts
(
    account_number_uuid UUID NOT NULL PRIMARY KEY,
    trader_identity_id_uuid UUID  NOT NULL,
    trader_number_value  VARCHAR(15)  NOT NULL,
    status_value VARCHAR(255) NOT NULL
);

CREATE TABLE transactions
(
    transaction_number_uuid UUID           NOT NULL PRIMARY KEY,
    account_number_uuid     UUID           NOT NULL,
    transaction_type_value   VARCHAR(255)   NOT NULL,
    value_value_value   DECIMAL(19, 2) NOT NULL,
    value_value_currency_code     VARCHAR(3)     NOT NULL,
    transaction_date   TIMESTAMP      NOT NULL,
    CONSTRAINT fk_transaction_account_number FOREIGN KEY (account_number_uuid) REFERENCES accounts (account_number_uuid)
);

CREATE TABLE wallets
(
    wallet_id_uuid      UUID           NOT NULL PRIMARY KEY,
    account_number_uuid UUID           NOT NULL,
    funds_value     DECIMAL(19, 2) NOT NULL,
    funds_code  VARCHAR(3)     NOT NULL,
    CONSTRAINT fk_wallet_account_number FOREIGN KEY (account_number_uuid) REFERENCES accounts (account_number_uuid)
);

CREATE TABLE quotes
(
    id SERIAL NOT NULL PRIMARY KEY,
    quote_number_uuid                            UUID           NOT NULL,
    requester_identity_id_uuid               UUID           NOT NULL,
    expiration_date_expiration_date TIMESTAMP      NOT NULL,
    best_exchange_rate_currency_to_sell_code VARCHAR(3)     NOT NULL,
    best_exchange_rate_currency_to_buy_code  VARCHAR(3)     NOT NULL,
    best_exchange_rate_rate_value_value   DECIMAL(15, 2) NOT NULL,
    money_to_exchange_value_value                   DECIMAL(15, 2) NOT NULL,
    money_to_exchange_currency_code                VARCHAR(3)     NOT NULL,
    money_exchanged_value_value                     DECIMAL(15, 2) NOT NULL,
    money_exchanged_currency_code             VARCHAR(3)     NOT NULL,
    quote_status_status                              VARCHAR(255)   NOT NULL
);

CREATE TABLE currency_pairs
(
    id SERIAL NOT NULL PRIMARY KEY,
    currency_pair_id_uuid UUID           NOT NULL,
    base_currency_code    VARCHAR(3)     NOT NULL,
    target_currency_code  VARCHAR(3)     NOT NULL,
    base_rate_value        DECIMAL(15, 2) NOT NULL,
    adjusted_rate_value  DECIMAL(15, 2),
    status_status           VARCHAR(50)    NOT NULL
);
CREATE TABLE negotiations
(
    id SERIAL NOT NULL PRIMARY KEY,
    negotiation_id_uuid             UUID           NOT NULL UNIQUE,
    negotiator_identity_id_uuid     UUID           NOT NULL,
    operator_operator_id_uuid                UUID,
    expiration_date            TIMESTAMP,
    target_currency_code            VARCHAR(3)     NOT NULL,
    base_currency_code              VARCHAR(3)     NOT NULL,
    proposed_exchange_amount_value_value   DECIMAL(15, 2) NOT NULL,
    proposed_exchange_amount_currency_code VARCHAR(3)     NOT NULL,
    negotiation_rate_proposed_rate_value      DECIMAL(15, 2) NOT NULL,
    negotiation_rate_base_exchange_rate_value        DECIMAL(15, 2) NOT NULL,
    negotiation_rate_difference_in_percentage_value   DECIMAL(15, 2) NOT NULL,
    status_status                     varchar(255)
);

CREATE TABLE risk_assessments
(
    id SERIAL NOT NULL PRIMARY KEY,
    risk_assessment_number_uuid UUID        NOT NULL,
    negotiator_identity_id_uuid UUID        NOT NULL,
    risk_level_level             VARCHAR(10) NOT NULL
);

CREATE TABLE risk_lines
(
    id SERIAL NOT NULL PRIMARY KEY,
    risk_line_id_uuid                    UUID           NOT NULL,
    negotiation_id_uuid                  UUID           NOT NULL,
    risk_negotiation_value_value   DECIMAL(15, 2) NOT NULL,
    risk_negotiation_value_code CHAR(3)        NOT NULL,
    risk_assessment_id INT NOT NULL,
    CONSTRAINT fk_risk_assessment_id FOREIGN KEY (risk_assessment_id) REFERENCES risk_assessments (id)
);

CREATE TABLE supported_currencies
(
    id SERIAL NOT NULL PRIMARY KEY,
    supported_currency_id_uuid UUID           NOT NULL,
    base_currency_code         VARCHAR(3)     NOT NULL,
    target_currency_code       VARCHAR(3)     NOT NULL,
    rate_value_value                  DECIMAL(15, 2) NOT NULL,
    status_status                VARCHAR(255)   NOT NULL
);
CREATE TABLE new_client_promotions
(
    id SERIAL NOT NULL PRIMARY KEY,
    identity_id_uuid         UUID    NOT NULL,
    account_activated   BOOLEAN NOT NULL,
    negotiation_created BOOLEAN NOT NULL
);

CREATE TABLE promotions
(
    id SERIAL NOT NULL PRIMARY KEY,
    promotion_number_uuid UUID         NOT NULL,
    target_customer_identity_id_uuid      UUID         NOT NULL,
    type   VARCHAR(255) NOT NULL
);

INSERT INTO identities (identity_id_uuid, version, pesel_value, first_name_value, surname_value, email_value) VALUES
                                                                            ('123e4567-e89b-12d3-a456-426655440000', 0,'85070465418', 'Jan', 'Kowalski', 'jan.kowalski@gmail.com'),
                                                                            ('123e4567-e89b-12d3-a456-426655440001', 0, '74062373642', 'Piotr', 'Nowak', 'piotr.nowak@gmail.com'),
                                                                            ('123e4567-e89b-12d3-a456-426655440002', 0, '84032785221', 'Adam', 'Wiśniewski', 'adam.wisniewski@gmail.com');



INSERT INTO accounts (account_number_uuid, trader_identity_id_uuid, trader_number_value, status_value)
VALUES
    ('9c3627e7-d5be-4f7e-8e52-4b7fca97d0a0', '123e4567-e89b-12d3-a456-426655440000', 'ABC-01-2023-123', 'INACTIVE');

INSERT INTO transactions (transaction_number_uuid, account_number_uuid, transaction_type_value, value_value_value, value_value_currency_code, transaction_date)
VALUES
    ('f49c5c94-3b0e-467c-94c1-2b8356db8cfa', '9c3627e7-d5be-4f7e-8e52-4b7fca97d0a0', 'CARD', 100.00, 'PLN', CURRENT_TIMESTAMP);

INSERT INTO wallets (wallet_id_uuid, account_number_uuid, funds_value, funds_code)
VALUES
    ('ea1f5ded-9a48-4083-a7b7-3b526dbf1768', '9c3627e7-d5be-4f7e-8e52-4b7fca97d0a0', 100.00, 'PLN');


INSERT INTO accounts (account_number_uuid, trader_identity_id_uuid, trader_number_value, status_value)
VALUES
    ('3f0f3b8e-6d75-4f61-9c8a-4a64f9e6b267', '123e4567-e89b-12d3-a456-426655440001', 'XYZ-01-2023-123', 'INACTIVE');

INSERT INTO transactions (transaction_number_uuid, account_number_uuid, transaction_type_value, value_value_value, value_value_currency_code, transaction_date)
VALUES
    ('7f7ccae0-8ad9-4754-a361-b37a87c2d0c8', '3f0f3b8e-6d75-4f61-9c8a-4a64f9e6b267', 'CARD', 500.00, 'PLN', CURRENT_TIMESTAMP);

INSERT INTO wallets (wallet_id_uuid, account_number_uuid, funds_value, funds_code)
VALUES
    ('4cd50a81-4bb4-4e2a-bf62-58c8ed51307b', '3f0f3b8e-6d75-4f61-9c8a-4a64f9e6b267', 500.00, 'PLN');


