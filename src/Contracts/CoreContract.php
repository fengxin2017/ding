<?php

namespace Fengxin2017\Ding\Contracts;

use Exception;

/**
 * Interface CoreContract.
 */
interface CoreContract
{
    /**
     * @param string $token
     *
     * @return mixed
     */
    public function setToken(string $token);

    /**
     * @return string
     */
    public function getToken(): string;

    /**
     * @param string $secret
     *
     * @return mixed
     */
    public function setSecret(string $secret);

    /**
     * @return string
     */
    public function getSecret(): string;

    /**
     * @param string $title
     *
     * @return mixed
     */
    public function setTitle(string $title);

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @param string $description
     *
     * @return mixed
     */
    public function setDescription(string $description);

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @param bool $trace
     *
     * @return mixed
     */
    public function setTrace(bool $trace);

    /**
     * @return bool
     */
    public function getTrace(): bool;

    /**
     * @param bool $limit
     *
     * @return mixed
     */
    public function setLimit(bool $limit);

    /**
     * @return bool
     */
    public function getLimit(): bool;

    /**
     * @param int $reportFrequency
     *
     * @return mixed
     */
    public function setReportFrequency(int $reportFrequency);

    /**
     * @return int
     */
    public function getReportFrequency(): int;

    /**
     * @param string $text
     *
     * @return mixed
     */
    public function text(string $text);

    /**
     * @param string $markdown
     *
     * @return mixed
     */
    public function markdown(string $markdown);

    /**
     * @param \Exception $exception
     *
     * @return mixed
     */
    public function exception(Exception $exception);
}
