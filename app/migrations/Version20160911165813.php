<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160911165813 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("CREATE SEQUENCE public.user_id_seq INCREMENT BY 1 MINVALUE 1 START 1;");
        $this->addSql("
        CREATE TABLE public.user
        (
            id INT NOT NULL DEFAULT nextval('public.user_id_seq'),
            email TEXT NOT NULL,
            first_name TEXT DEFAULT NULL,
            last_name TEXT DEFAULT NULL,
            middle_name TEXT DEFAULT NULL,
            salt TEXT NOT NULL,
            password TEXT NOT NULL,
            role TEXT NOT NULL,
            registered_at TIMESTAMP(0) NOT NULL,
            is_email_confirmed BOOLEAN DEFAULT FALSE  NOT NULL,
            is_active BOOLEAN DEFAULT TRUE  NOT NULL,
            PRIMARY KEY(id)
        );
        ");
        $this->addSql("CREATE UNIQUE INDEX unique_user_email ON public.user (email);");
        $this->addSql("CREATE INDEX user_first_name_index ON public.user (first_name);");
        $this->addSql("CREATE INDEX user_last_name_index ON public.user (last_name);");
        $this->addSql("CREATE INDEX user_middle_name_index ON public.user (middle_name);");
        $this->addSql("CREATE INDEX user_role_index ON public.user (role);");
        $this->addSql("CREATE INDEX user_registered_at_index ON public.user (registered_at);");
        $this->addSql("CREATE INDEX user_is_email_confirmed_index ON public.user (is_email_confirmed);");
        $this->addSql("CREATE INDEX user_is_active_index ON public.user (is_active);");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DROP TABLE public.user;");
        $this->addSql("DROP SEQUENCE public.user_id_seq;");
    }
}
