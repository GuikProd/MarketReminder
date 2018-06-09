<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Application\Command;

use App\Application\Command\Interfaces\TranslationWarmerCommandInterface;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWarmerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TranslationWarmerCommand.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class TranslationWarmerCommand extends Command implements TranslationWarmerCommandInterface
{
    /**
     * @var CloudTranslationWarmerInterface
     */
    private $redisTranslationWarmer;

    /**
     * {@inheritdoc}
     */
    public function __construct(CloudTranslationWarmerInterface $redisTranslationWarmer)
    {
        $this->redisTranslationWarmer = $redisTranslationWarmer;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:translation-warm')
            ->setDescription('Allow to warm the translation for a given channel and locale.')
            ->setHelp('This command call the GCP Translation API and translate (using the locale passed) the whole channel passed.')
            ->addArgument('channel', InputArgument::REQUIRED, 'The channel of the file to translate.')
            ->addArgument('locale', InputArgument::REQUIRED, 'The locale used to translate.');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('<info>The warm process is about to begin.</info>');

        if (!$this->redisTranslationWarmer->warmTranslations(
            $input->getArgument('channel'),
            $input->getArgument('locale'))
        ) {
            $output->write('<error>The translations can\'t be warmed or are already proceed, please retry.</error>');

            return;
        }

        $output->write('<info>The warm process is finished.</info>');
    }
}
