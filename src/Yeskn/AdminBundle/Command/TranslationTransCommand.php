<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jaggle
 * Create: 2018-10-28 10:15:27
 */

namespace Yeskn\AdminBundle\Command;

use Doctrine\ORM\EntityRepository;
use GuzzleHttp\Client;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yeskn\MainBundle\Entity\Translation;
use Yeskn\Support\Command\AbstractCommand;

class TranslationTransCommand extends AbstractCommand
{
    /**
     * @var Client
     */
    private $client;
    private $baiduId;
    private $baiduKey;

    protected function configure()
    {
        $this->setName('translation:trans');
        $this->setDescription('translate zh_CN token to another lang(if these are empty)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->client = new Client();

        /** @var EntityRepository $transRepo */
        $transRepo = $this->doctrine()->getRepository('YesknMainBundle:Translation');

        /** @var Translation[] $trans */
        $trans = $transRepo->findAll();

        $this->baiduId = $this->getOption('baiduTransAppId');
        $this->baiduKey = $this->getOption('baiduTransKey');

        foreach ($trans as $tran) {
            if (empty($tran->getChinese())) {
                continue;
            }

            if (strlen($tran->getEnglish()) === 0) {
                $ret = $this->translate($tran->getChinese(), 'zh', 'en');
                $tran->setEnglish($ret);
            }

            if (strlen($tran->getJapanese()) === 0) {
                $ret = $this->translate($tran->getChinese(), 'zh', 'jp');
                $tran->setJapanese($ret);
            }

            if (strlen($tran->getTaiwanese()) === 0) {
                $ret = $this->translate($tran->getChinese(), 'zh', 'cht');
                $tran->setTaiwanese($ret);
            }

            $this->em()->flush();
        }

        $this->io()->success('finished!');
    }

    public function translate($q, $from, $to)
    {
        $salt = (string) mt_rand(100000, 999999);

        $response = $this->client->post('https://fanyi-api.baidu.com/api/trans/vip/translate', [
            'form_params' => [
                'q' => $q,
                'from' => $from,
                'to' => $to,
                'appid' => $this->baiduId,
                'salt' => $salt,
                'sign' => md5((string) $this->baiduId . $q . $salt . $this->baiduKey)
            ]
        ]);

        $result = $response->getBody()->getContents();
        $result = json_decode($result, true);

        if (!empty($result['trans_result'])) {
            return $result['trans_result'][0]['dst'];
        } else {
            throw new \Exception('translate token ' . $q . ' error: ' . json_encode($result));
        }
    }
}
