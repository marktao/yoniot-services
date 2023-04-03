<?php

use Hyperf\Jet\AbstractClient;
use Hyperf\Jet\NodeSelector\NodeSelector;
use Hyperf\Jet\Packer\JsonEofPacker;
use Hyperf\Jet\Transporter\StreamSocketTransporter;
use Hyperf\Rpc\Contract\DataFormatterInterface;
use Hyperf\Rpc\Contract\PackerInterface;
use Hyperf\Rpc\Contract\PathGeneratorInterface;
use Hyperf\Rpc\Contract\TransporterInterface;
use think\facade\Config;

/**
 * EmailServices.php:
 * Create by: 有你物联：http://community.yoniot.cn/smartcommunity.html
 * User: Mark 437629292@qq.com
 * Date: 2023/4/3
 */
class EmailServices extends AbstractClient
{
    public function __construct(
        string $service = 'EmailServices',
        TransporterInterface $transporter = null,
        PackerInterface $packer = null,
        ?DataFormatterInterface $dataFormatter = null,
        ?PathGeneratorInterface $pathGenerator = null
    ) {
        // Specific the transporter here, you could also retrieve the transporter from ProtocolManager or passing by constructor.
        $transporter = new StreamSocketTransporter();
        $config= [];
        $nodeSelector = new NodeSelector(Config::get('rpc.consul_ip'), Config::get('rpc.consul_port'), $config);
        [$transporter->host, $transporter->port] = $nodeSelector->selectRandomNode($service, 'jsonrpc');
        // Specific the packer here, you could also retrieve the packer from ProtocolManager or passing by constructor.
        $packer = new JsonEofPacker();
        parent::__construct($service, $transporter, $packer, $dataFormatter, $pathGenerator);
    }
}