-- Adminer 4.8.1 PostgreSQL 13.11 dump

\connect "super_tokens";

DROP TABLE IF EXISTS "confirmation_codes";
CREATE TABLE "public"."confirmation_codes" (
    "id" uuid NOT NULL,
    "user_id" uuid,
    "code" character varying(10) NOT NULL,
    "expire_at" timestamp(0) NOT NULL,
    "action" character varying(50) NOT NULL,
    "created_at" timestamp(0) DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT "confirmation_codes_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

CREATE INDEX "idx_7ac5d2e1a76ed395" ON "public"."confirmation_codes" USING btree ("user_id");

COMMENT ON COLUMN "public"."confirmation_codes"."id" IS '(DC2Type:uuid)';

COMMENT ON COLUMN "public"."confirmation_codes"."user_id" IS '(DC2Type:uuid)';

TRUNCATE "confirmation_codes";
INSERT INTO "confirmation_codes" ("id", "user_id", "code", "expire_at", "action", "created_at") VALUES
('5bbbf89a-d222-40e4-8ac5-1e42ece9a603',	'3cfdf0b8-5ffd-4884-8a4f-1b7d1294d623',	'292137',	'2023-10-12 04:04:01',	'EmailConfirmation',	'2023-10-11 04:03:01'),
('cd328671-9ac9-4cf3-8204-719e965a3993',	'3cfdf0b8-5ffd-4884-8a4f-1b7d1294d623',	'351151',	'2023-10-12 04:09:12',	'PasswordReset',	'2023-10-11 04:08:12');

DROP TABLE IF EXISTS "doctrine_migration_versions";
CREATE TABLE "public"."doctrine_migration_versions" (
    "version" character varying(191) NOT NULL,
    "executed_at" timestamp(0),
    "execution_time" integer,
    CONSTRAINT "doctrine_migration_versions_pkey" PRIMARY KEY ("version")
) WITH (oids = false);

TRUNCATE "doctrine_migration_versions";
INSERT INTO "doctrine_migration_versions" ("version", "executed_at", "execution_time") VALUES
('DoctrineMigrations\Version20220421110335',	'2023-10-11 04:02:24',	71);

DROP TABLE IF EXISTS "refresh_tokens";
DROP SEQUENCE IF EXISTS refresh_tokens_id_seq;
CREATE SEQUENCE refresh_tokens_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1;

CREATE TABLE "public"."refresh_tokens" (
    "id" integer DEFAULT nextval('refresh_tokens_id_seq') NOT NULL,
    "refresh_token" character(128),
    "username" character(255),
    "valid" date NOT NULL,
    CONSTRAINT "refresh_tokens_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "refresh_tokens_refresh_token_key" UNIQUE ("refresh_token")
) WITH (oids = false);

TRUNCATE "refresh_tokens";
INSERT INTO "refresh_tokens" ("id", "refresh_token", "username", "valid") VALUES
(1,	'130d202ad7d1d40cc7a29a2bafdbdf0dd8a24f7866670e16156ca515a025db0844eaad76fd081c651d0e70d71d57ab4ddb33fc906d7a1dcfa1d62902583581ce',	'atologist.palak@gmail.com                                                                                                                                                                                                                                      ',	'2023-11-10'),
(2,	'812c586e0ed154372300a811f67f15d88c7cffcf32019fa297db64ee8f71b49cce74dfaace61c9529b2980911cfe0217cdaf31a4fe08327c745ce5fbcfebd746',	'atologist.palak@gmail.com                                                                                                                                                                                                                                      ',	'2023-11-10');

DROP TABLE IF EXISTS "request_logs";
CREATE TABLE "public"."request_logs" (
    "id" uuid NOT NULL,
    "user_id" uuid,
    "method" character varying(10) NOT NULL,
    "uri" character varying(255) NOT NULL,
    "status_code" integer NOT NULL,
    "headers" text,
    "query_params" text,
    "body_params" text,
    "response_body" text,
    "exception" text,
    "request_duration" double precision,
    "created_at" timestamp(0) DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT "request_logs_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

CREATE INDEX "idx_8f28e1a6a76ed395" ON "public"."request_logs" USING btree ("user_id");

CREATE INDEX "requestlogscreatedatmethoduristatuscodeidx" ON "public"."request_logs" USING btree ("created_at", "method", "uri", "status_code");

CREATE INDEX "requestlogscreatedatstatuscodesdx" ON "public"."request_logs" USING btree ("created_at", "status_code");

CREATE INDEX "requestlogscreatedaturiidx" ON "public"."request_logs" USING btree ("created_at", "uri");

CREATE INDEX "requestlogscreatedatuserididx" ON "public"."request_logs" USING btree ("created_at", "user_id");

COMMENT ON COLUMN "public"."request_logs"."id" IS '(DC2Type:uuid)';

COMMENT ON COLUMN "public"."request_logs"."user_id" IS '(DC2Type:uuid)';

TRUNCATE "request_logs";
INSERT INTO "request_logs" ("id", "user_id", "method", "uri", "status_code", "headers", "query_params", "body_params", "response_body", "exception", "request_duration", "created_at") VALUES
('9718280b-126e-4ed0-8243-50991572e6c5',	NULL,	'POST',	'http://127.0.0.1:8001/api/v1/register',	200,	'{"content-type":["application\/json"],"user-agent":["PostmanRuntime\/7.33.0"],"accept":["*\/*"],"postman-token":["1131afc1-8a9c-4629-9f55-420b34b9d8fb"],"host":["127.0.0.1:8001"],"accept-encoding":["gzip, deflate, br"],"connection":["keep-alive"],"content-length":["200"],"x-php-ob-level":["1"]}',	'[]',	'{"fullName":"Palak","phoneNumber":"8741216751","role":"ROLE_USER","email":"atologist.palak@gmail.com","password":"password","confirmPassword":"password"}',	NULL,	NULL,	6.8677139282227,	'2023-10-11 04:03:05'),
('f44ddca7-01d1-47d7-b608-d3b96a9b134d',	NULL,	'POST',	'http://127.0.0.1:8001/api/v1/login',	200,	'{"content-type":["application\/json"],"user-agent":["PostmanRuntime\/7.33.0"],"accept":["*\/*"],"postman-token":["de47658c-cc61-418c-8b95-ee4640154004"],"host":["127.0.0.1:8001"],"accept-encoding":["gzip, deflate, br"],"connection":["keep-alive"],"content-length":["80"],"x-php-ob-level":["1"]}',	'[]',	'{"username":"atologist.palak@gmail.com","password":"password"}',	NULL,	NULL,	2.0053050518036,	'2023-10-11 04:03:44'),
('dfe886a2-a952-4789-9339-f2808325bb5b',	NULL,	'POST',	'http://127.0.0.1:8001/api/v1/verify-otp',	200,	'{"content-type":["application\/json"],"user-agent":["PostmanRuntime\/7.33.0"],"accept":["*\/*"],"postman-token":["e3b6bb80-29e0-448d-8988-2675e94b02cb"],"host":["127.0.0.1:8001"],"accept-encoding":["gzip, deflate, br"],"connection":["keep-alive"],"content-length":["61"],"x-php-ob-level":["1"]}',	'[]',	'{"otp":"292137","action":"EmailConfirmation"}',	NULL,	NULL,	2.9494500160217,	'2023-10-11 04:04:40'),
('795bd3ae-fb57-4fcc-a97b-6101640256d3',	NULL,	'GET',	'http://127.0.0.1:8001/api/doc',	200,	'{"host":["127.0.0.1:8001"],"connection":["keep-alive"],"sec-ch-ua":["\"Google Chrome\";v=\"117\", \"Not;A=Brand\";v=\"8\", \"Chromium\";v=\"117\""],"sec-ch-ua-mobile":["?0"],"sec-ch-ua-platform":["\"Windows\""],"upgrade-insecure-requests":["1"],"user-agent":["Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/117.0.0.0 Safari\/537.36"],"accept":["text\/html,application\/xhtml+xml,application\/xml;q=0.9,image\/avif,image\/webp,image\/apng,*\/*;q=0.8,application\/signed-exchange;v=b3;q=0.7"],"sec-fetch-site":["none"],"sec-fetch-mode":["navigate"],"sec-fetch-user":["?1"],"sec-fetch-dest":["document"],"accept-encoding":["gzip, deflate, br"],"accept-language":["en-US,en;q=0.9"],"cookie":["__stripe_mid=95daa865-9103-4e0b-b2ee-f3283e34acd60a37fc; sonata_sidebar_hide=0; PHPSESSID=bub26nvpmsnlou59f7gachbmdi"],"x-php-ob-level":["1"]}',	'[]',	'[]',	NULL,	NULL,	0.097394943237305,	'2023-10-11 04:05:25'),
('e8ce59fb-2cac-4fb4-89bc-da0575883171',	NULL,	'GET',	'http://127.0.0.1:8001/doc/default',	200,	'{"host":["127.0.0.1:8001"],"connection":["keep-alive"],"sec-ch-ua":["\"Google Chrome\";v=\"117\", \"Not;A=Brand\";v=\"8\", \"Chromium\";v=\"117\""],"sec-ch-ua-mobile":["?0"],"sec-ch-ua-platform":["\"Windows\""],"upgrade-insecure-requests":["1"],"user-agent":["Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/117.0.0.0 Safari\/537.36"],"accept":["text\/html,application\/xhtml+xml,application\/xml;q=0.9,image\/avif,image\/webp,image\/apng,*\/*;q=0.8,application\/signed-exchange;v=b3;q=0.7"],"sec-fetch-site":["same-origin"],"sec-fetch-mode":["navigate"],"sec-fetch-user":["?1"],"sec-fetch-dest":["document"],"referer":["http:\/\/127.0.0.1:8001\/api\/doc"],"accept-encoding":["gzip, deflate, br"],"accept-language":["en-US,en;q=0.9"],"cookie":["__stripe_mid=95daa865-9103-4e0b-b2ee-f3283e34acd60a37fc; sonata_sidebar_hide=0; PHPSESSID=bub26nvpmsnlou59f7gachbmdi"],"x-php-ob-level":["1"]}',	'[]',	'[]',	NULL,	NULL,	1.3271300792694,	'2023-10-11 04:05:30'),
('12ad45bd-8b35-48ce-8563-c070e00693e3',	NULL,	'GET',	'http://127.0.0.1:8001/doc/default',	200,	'{"host":["127.0.0.1:8001"],"connection":["keep-alive"],"cache-control":["max-age=0"],"sec-ch-ua":["\"Google Chrome\";v=\"117\", \"Not;A=Brand\";v=\"8\", \"Chromium\";v=\"117\""],"sec-ch-ua-mobile":["?0"],"sec-ch-ua-platform":["\"Windows\""],"upgrade-insecure-requests":["1"],"user-agent":["Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/117.0.0.0 Safari\/537.36"],"accept":["text\/html,application\/xhtml+xml,application\/xml;q=0.9,image\/avif,image\/webp,image\/apng,*\/*;q=0.8,application\/signed-exchange;v=b3;q=0.7"],"sec-fetch-site":["same-origin"],"sec-fetch-mode":["navigate"],"sec-fetch-user":["?1"],"sec-fetch-dest":["document"],"referer":["http:\/\/127.0.0.1:8001\/api\/doc"],"accept-encoding":["gzip, deflate, br"],"accept-language":["en-US,en;q=0.9"],"cookie":["__stripe_mid=95daa865-9103-4e0b-b2ee-f3283e34acd60a37fc; sonata_sidebar_hide=0; PHPSESSID=bub26nvpmsnlou59f7gachbmdi"],"x-php-ob-level":["1"]}',	'[]',	'[]',	NULL,	NULL,	0.32280015945435,	'2023-10-11 04:06:34'),
('338e0ea2-6c13-421b-8e1a-087d1ce04bd6',	NULL,	'POST',	'http://127.0.0.1:8001/api/v1/login/guest',	500,	'{"user-agent":["PostmanRuntime\/7.33.0"],"accept":["*\/*"],"postman-token":["71be9589-7450-4a02-b1a4-3d5619e30182"],"host":["127.0.0.1:8001"],"accept-encoding":["gzip, deflate, br"],"connection":["keep-alive"],"content-length":["0"],"x-php-ob-level":["1"]}',	'[]',	'[]',	NULL,	'
#0 C:\xampp\htdocs\super-tokens\src\Service\Framework\ExtendedParamConverter.php(42): FOS\RestBundle\Request\RequestBodyParamConverter->apply(Object(Symfony\Component\HttpFoundation\Request), Object(Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter))
#1 C:\xampp\htdocs\super-tokens\vendor\sensio\framework-extra-bundle\src\Request\ParamConverter\ParamConverterManager.php(77): App\Service\Framework\ExtendedParamConverter->apply(Object(Symfony\Component\HttpFoundation\Request), Object(Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter))
#2 C:\xampp\htdocs\super-tokens\vendor\sensio\framework-extra-bundle\src\Request\ParamConverter\ParamConverterManager.php(48): Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterManager->applyConverter(Object(Symfony\Component\HttpFoundation\Request), Object(Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter))
#3 C:\xampp\htdocs\super-tokens\vendor\sensio\framework-extra-bundle\src\EventListener\ParamConverterListener.php(72): Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterManager->apply(Object(Symfony\Component\HttpFoundation\Request), Array)
#4 C:\xampp\htdocs\super-tokens\vendor\symfony\event-dispatcher\Debug\WrappedListener.php(117): Sensio\Bundle\FrameworkExtraBundle\EventListener\ParamConverterListener->onKernelController(Object(Symfony\Component\HttpKernel\Event\ControllerEvent), ''kernel.controll...'', Object(Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher))
#5 C:\xampp\htdocs\super-tokens\vendor\symfony\event-dispatcher\EventDispatcher.php(230): Symfony\Component\EventDispatcher\Debug\WrappedListener->__invoke(Object(Symfony\Component\HttpKernel\Event\ControllerEvent), ''kernel.controll...'', Object(Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher))
#6 C:\xampp\htdocs\super-tokens\vendor\symfony\event-dispatcher\EventDispatcher.php(59): Symfony\Component\EventDispatcher\EventDispatcher->callListeners(Array, ''kernel.controll...'', Object(Symfony\Component\HttpKernel\Event\ControllerEvent))
#7 C:\xampp\htdocs\super-tokens\vendor\symfony\event-dispatcher\Debug\TraceableEventDispatcher.php(154): Symfony\Component\EventDispatcher\EventDispatcher->dispatch(Object(Symfony\Component\HttpKernel\Event\ControllerEvent), ''kernel.controll...'')
#8 C:\xampp\htdocs\super-tokens\vendor\symfony\http-kernel\HttpKernel.php(151): Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher->dispatch(Object(Symfony\Component\HttpKernel\Event\ControllerEvent), ''kernel.controll...'')
#9 C:\xampp\htdocs\super-tokens\vendor\symfony\http-kernel\HttpKernel.php(75): Symfony\Component\HttpKernel\HttpKernel->handleRaw(Object(Symfony\Component\HttpFoundation\Request), 1)
#10 C:\xampp\htdocs\super-tokens\vendor\symfony\http-kernel\Kernel.php(202): Symfony\Component\HttpKernel\HttpKernel->handle(Object(Symfony\Component\HttpFoundation\Request), 1, true)
#11 C:\xampp\htdocs\super-tokens\vendor\symfony\runtime\Runner\Symfony\HttpKernelRunner.php(35): Symfony\Component\HttpKernel\Kernel->handle(Object(Symfony\Component\HttpFoundation\Request))
#12 C:\xampp\htdocs\super-tokens\vendor\autoload_runtime.php(35): Symfony\Component\Runtime\Runner\Symfony\HttpKernelRunner->run()
#13 C:\xampp\htdocs\super-tokens\public\index.php(5): require_once(''C:\\xampp\\htdocs...'')
#14 {main}',	0.15839314460754,	'2023-10-11 04:07:57'),
('c3f1e507-310a-442f-8bae-41ad9ae7a825',	NULL,	'POST',	'http://127.0.0.1:8001/api/v1/reset-password',	200,	'{"content-type":["application\/json"],"user-agent":["PostmanRuntime\/7.33.0"],"accept":["*\/*"],"postman-token":["72d8161b-cb4f-474c-ae2f-65cdc1556f0e"],"host":["127.0.0.1:8001"],"accept-encoding":["gzip, deflate, br"],"connection":["keep-alive"],"content-length":["47"],"x-php-ob-level":["1"]}',	'[]',	'{"email":"atologist.palak@gmail.com"}',	NULL,	NULL,	3.6590929031372,	'2023-10-11 04:08:15'),
('8aa9732b-56e5-40e7-92bb-c9c1ec658a15',	NULL,	'POST',	'http://127.0.0.1:8001/api/v1/verify-otp',	422,	'{"content-type":["application\/json"],"user-agent":["PostmanRuntime\/7.33.0"],"accept":["*\/*"],"postman-token":["002c9fed-4f87-4097-b47a-293f0ded9bae"],"host":["127.0.0.1:8001"],"accept-encoding":["gzip, deflate, br"],"connection":["keep-alive"],"content-length":["57"],"x-php-ob-level":["1"]}',	'[]',	'{"otp":"351151","action":"PasswordReset"}',	NULL,	'verifyOTP.request.expired.code
#0 C:\xampp\htdocs\super-tokens\vendor\symfony\http-kernel\HttpKernel.php(163): App\Controller\Api\V1\SecurityClientController->verifyOTP(Object(Symfony\Component\HttpFoundation\Request), Object(App\VO\Protocol\Api\Security\VerifyOTPBody))
#1 C:\xampp\htdocs\super-tokens\vendor\symfony\http-kernel\HttpKernel.php(75): Symfony\Component\HttpKernel\HttpKernel->handleRaw(Object(Symfony\Component\HttpFoundation\Request), 1)
#2 C:\xampp\htdocs\super-tokens\vendor\symfony\http-kernel\Kernel.php(202): Symfony\Component\HttpKernel\HttpKernel->handle(Object(Symfony\Component\HttpFoundation\Request), 1, true)
#3 C:\xampp\htdocs\super-tokens\vendor\symfony\runtime\Runner\Symfony\HttpKernelRunner.php(35): Symfony\Component\HttpKernel\Kernel->handle(Object(Symfony\Component\HttpFoundation\Request))
#4 C:\xampp\htdocs\super-tokens\vendor\autoload_runtime.php(35): Symfony\Component\Runtime\Runner\Symfony\HttpKernelRunner->run()
#5 C:\xampp\htdocs\super-tokens\public\index.php(5): require_once(''C:\\xampp\\htdocs...'')
#6 {main}',	1.973060131073,	'2023-10-11 04:10:03'),
('e536b8f8-be91-4541-8fcb-dc0f55fd374f',	NULL,	'POST',	'http://127.0.0.1:8001/api/v1/verify-otp',	200,	'{"content-type":["application\/json"],"user-agent":["PostmanRuntime\/7.33.0"],"accept":["*\/*"],"postman-token":["0fdaa491-74c5-4eed-aaa5-a722aeff0190"],"host":["127.0.0.1:8001"],"accept-encoding":["gzip, deflate, br"],"connection":["keep-alive"],"content-length":["57"],"x-php-ob-level":["1"]}',	'[]',	'{"otp":"351151","action":"PasswordReset"}',	NULL,	NULL,	0.95545196533203,	'2023-10-11 04:10:31');

DROP TABLE IF EXISTS "sonata_user__user";
CREATE TABLE "public"."sonata_user__user" (
    "id" character varying(36) NOT NULL,
    "username" character varying(180) NOT NULL,
    "username_canonical" character varying(180) NOT NULL,
    "email" character varying(180) NOT NULL,
    "email_canonical" character varying(180) NOT NULL,
    "enabled" boolean NOT NULL,
    "salt" character varying(255),
    "password" character varying(255) NOT NULL,
    "last_login" timestamp(0),
    "confirmation_token" character varying(180),
    "password_requested_at" timestamp(0),
    "roles" text NOT NULL,
    "created_at" timestamp(0) NOT NULL,
    "updated_at" timestamp(0) NOT NULL,
    CONSTRAINT "sonata_user__user_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "uniq_4f797d592fc23a8" UNIQUE ("username_canonical"),
    CONSTRAINT "uniq_4f797d5a0d96fbf" UNIQUE ("email_canonical"),
    CONSTRAINT "uniq_4f797d5c05fb297" UNIQUE ("confirmation_token")
) WITH (oids = false);

COMMENT ON COLUMN "public"."sonata_user__user"."roles" IS '(DC2Type:array)';

TRUNCATE "sonata_user__user";

DROP TABLE IF EXISTS "user_addresses";
CREATE TABLE "public"."user_addresses" (
    "id" uuid NOT NULL,
    "user_id" uuid,
    "address" text NOT NULL,
    "created_at" timestamp(0) DEFAULT CURRENT_TIMESTAMP,
    "updated_at" timestamp(0),
    CONSTRAINT "user_addresses_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

CREATE INDEX "idx_6f2af8f2a76ed395" ON "public"."user_addresses" USING btree ("user_id");

COMMENT ON COLUMN "public"."user_addresses"."id" IS '(DC2Type:uuid)';

COMMENT ON COLUMN "public"."user_addresses"."user_id" IS '(DC2Type:uuid)';

TRUNCATE "user_addresses";

DROP TABLE IF EXISTS "users";
CREATE TABLE "public"."users" (
    "id" uuid NOT NULL,
    "full_name" character varying(150) NOT NULL,
    "email" character varying(255),
    "password" character varying(255),
    "salt" character varying(255),
    "phone_number" character varying(15) NOT NULL,
    "role" character varying(255),
    "profile_picture" character varying(50),
    "apple_id" character varying(100),
    "facebook_id" character varying(100),
    "google_id" character varying(100),
    "status" character varying(25) DEFAULT 'active' NOT NULL,
    "created_at" timestamp(0) DEFAULT CURRENT_TIMESTAMP,
    "updated_at" timestamp(0),
    CONSTRAINT "uniq_1483a5e96b01bc5b" UNIQUE ("phone_number"),
    CONSTRAINT "uniq_1483a5e9e7927c74" UNIQUE ("email"),
    CONSTRAINT "users_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

COMMENT ON COLUMN "public"."users"."id" IS '(DC2Type:uuid)';

TRUNCATE "users";
INSERT INTO "users" ("id", "full_name", "email", "password", "salt", "phone_number", "role", "profile_picture", "apple_id", "facebook_id", "google_id", "status", "created_at", "updated_at") VALUES
('3cfdf0b8-5ffd-4884-8a4f-1b7d1294d623',	'Palak',	'atologist.palak@gmail.com',	'$2y$13$ddnNoOPfvcFzaMZJ9V68zuxTChY5tdBXidtJ9qERtIJ/B6mq7gR7e',	NULL,	'8741216751',	'ROLE_USER',	NULL,	NULL,	NULL,	NULL,	'active',	'2023-10-11 04:03:01',	'2023-10-11 04:08:12');

ALTER TABLE ONLY "public"."confirmation_codes" ADD CONSTRAINT "fk_7ac5d2e1a76ed395" FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE NOT DEFERRABLE;

ALTER TABLE ONLY "public"."request_logs" ADD CONSTRAINT "fk_8f28e1a6a76ed395" FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL NOT DEFERRABLE;

ALTER TABLE ONLY "public"."user_addresses" ADD CONSTRAINT "fk_6f2af8f2a76ed395" FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE NOT DEFERRABLE;

-- 2023-10-11 09:54:18.086578+05:30
