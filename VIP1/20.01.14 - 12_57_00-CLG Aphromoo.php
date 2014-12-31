<?php exit() ?>--by CLG Aphromoo 174.56.209.94
rawset(_G, 'BuyItem', function(id)
p = CLoLPacket(0x81)
p.dwArg1 = 1
p.dwArg2 = 0
p:EncodeF(myHero.networkID)
p:Encode4(id)
SendPacket(p))